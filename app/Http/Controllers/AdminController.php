<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayslipMail;
use App\Models\FulltimeTimesheet;
use App\Models\ParttimeTimesheet;
use App\Models\StaffTimesheet;
use App\Models\UtilityTimesheet;
use App\Models\PayslipHistory;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        // Throttle: 3 attempts, then 60-second cooldown using session storage
        $attempts = $request->session()->get('admin_login_attempts', 0);
        $cooldownUntil = $request->session()->get('admin_login_cooldown_until');
        $now = now();

        if ($cooldownUntil && $now->lessThan(\Carbon\Carbon::parse($cooldownUntil))) {
            $secondsLeft = $now->diffInSeconds(\Carbon\Carbon::parse($cooldownUntil));
            return back()->with('error', 'Too many attempts. Please wait ' . $secondsLeft . ' seconds before trying again.');
        }

        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:admin'
        ]);

        // Find admin user in database
        $admin = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if ($admin && password_verify($request->password, $admin->password)) {
            // Additional security: Check if password needs rehashing (for upgraded security)
            if (password_needs_rehash($admin->password, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536,
                'time_cost' => 4,
                'threads' => 3
            ])) {
                // Update password hash with new security parameters
                $newHash = password_hash($request->password, PASSWORD_ARGON2ID, [
                    'memory_cost' => 65536,
                    'time_cost' => 4,
                    'threads' => 3
                ]);
                DB::table('users')->where('id', $admin->id)->update(['password' => $newHash]);
            }

            // Successful login: reset attempt counters
            $request->session()->forget('admin_login_attempts');
            $request->session()->forget('admin_login_cooldown_until');

            // Start admin session
            $request->session()->put('user_id', $admin->id);
            $request->session()->put('user_name', $admin->name);
            $request->session()->put('user_role', 'admin');
            $request->session()->put('is_admin', true);

            return redirect('/dashboard');
        }

        // Failed login: increment attempts and apply cooldown when reaching 3
        $attempts++;
        if ($attempts >= 3) {
            $request->session()->put('admin_login_cooldown_until', $now->addSeconds(60)->toDateTimeString());
            $request->session()->put('admin_login_attempts', 0);
            return back()->with('error', 'Too many attempts. Please wait 60 seconds before trying again.');
        }

        $request->session()->put('admin_login_attempts', $attempts);
        return back()->with('error', 'Invalid admin credentials or you are not authorized as admin. Attempts: ' . $attempts . '/3');
    }

    public function dashboard(Request $request)
    {
        // Check if user is logged in and is admin
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return redirect('/')->with('error', 'Please login as admin first.');
        }

        // Get employee statistics
        $stats = $this->getEmployeeStatistics();

        // Return the dedicated admin dashboard with statistics
        return view('admin.dashboard', [
            'totalEmployees' => $stats['totalEmployees'],
            'totalFulltimeInstructors' => $stats['totalFulltimeInstructors'],
            'totalParttimeInstructors' => $stats['totalParttimeInstructors'],
            'totalStaff' => $stats['totalStaff'],
            'totalUtility' => $stats['totalUtility'],
        ]);
    }

    public function salaryAdjustment(Request $request)
    {
        // Check if user is logged in and is admin
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return redirect('/')->with('error', 'Please login as admin first.');
        }

        // Return the salary adjustment view
        return view('salary.adjustment');
    }

    /**
     * Display history of sent payslips with optional filters
     */
    public function history(Request $request)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return redirect('/')->with('error', 'Please login as admin first.');
        }

        try {
            $query = \DB::table('payslip_histories')->whereNull('deleted_at')->orderByDesc('sent_at');

            if ($request->filled('batch_id')) {
                $query->where('batch_id', $request->batch_id);
            }
            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $histories = $query->paginate(20)->appends($request->query());

            // Distinct last 20 batch ids for quick filter
            $batches = \DB::table('payslip_histories')
                ->select('batch_id', \DB::raw('count(*) as total'), \DB::raw("sum(case when status='sent' then 1 else 0 end) as sent"), \DB::raw("sum(case when status='failed' then 1 else 0 end) as failed"))
                ->groupBy('batch_id')
                ->orderByDesc(\DB::raw('max(sent_at)'))
                ->limit(20)
                ->get();

            return view('admin.history', [
                'histories' => $histories,
                'batches' => $batches,
                'tableReady' => true,
            ]);
        } catch (\Throwable $e) {
            // Likely table missing or not migrated yet
            return view('admin.history', [
                'histories' => collect(),
                'batches' => collect(),
                'tableReady' => false,
                'errorMessage' => $e->getMessage(),
            ]);
        }
    }

    // Soft delete a history record (AJAX or form)
    public function historySoftDelete($id)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history = PayslipHistory::find($id);
        if (!$history) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $history->delete();
        return response()->json(['message' => 'Record moved to trash.']);
    }

    // Permanently delete a history record
    public function historyForceDelete($id)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history = PayslipHistory::withTrashed()->find($id);
        if (!$history) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $history->forceDelete();
        return response()->json(['message' => 'Record permanently deleted.']);
    }

    // Display trash records (soft deleted)
    public function trash(Request $request)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return redirect('/')->with('error', 'Please login as admin first.');
        }

        try {
            $query = \DB::table('payslip_histories')->whereNotNull('deleted_at')->orderByDesc('deleted_at');

            if ($request->filled('batch_id')) {
                $query->where('batch_id', $request->batch_id);
            }
            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $histories = $query->paginate(20)->appends($request->query());

            // Distinct last 20 batch ids for quick filter
            $batches = \DB::table('payslip_histories')
                ->select('batch_id', \DB::raw('count(*) as total'), \DB::raw("sum(case when status='sent' then 1 else 0 end) as sent"), \DB::raw("sum(case when status='failed' then 1 else 0 end) as failed"))
                ->whereNotNull('deleted_at')
                ->groupBy('batch_id')
                ->orderByDesc(\DB::raw('max(deleted_at)'))
                ->limit(20)
                ->get();

            return view('admin.trash', [
                'histories' => $histories,
                'batches' => $batches,
                'tableReady' => true,
            ]);
        } catch (\Throwable $e) {
            // Likely table missing or not migrated yet
            return view('admin.trash', [
                'histories' => collect(),
                'batches' => collect(),
                'tableReady' => false,
                'errorMessage' => $e->getMessage(),
            ]);
        }
    }

    // Restore a soft deleted history record
    public function historyRestore($id)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $history = PayslipHistory::withTrashed()->find($id);
        if (!$history) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $history->restore();
        return response()->json(['message' => 'Record restored to history.']);
    }

    /**
     * Get comprehensive employee statistics with unique person counts
     *
     * @return array
     */
    private function getEmployeeStatistics()
    {
        // Count unique employees from timesheet tables (these are the actual working employees)
        $totalFulltimeInstructors = $this->countUniqueEmployees(FulltimeTimesheet::class);
        $totalParttimeInstructors = $this->countUniqueEmployees(ParttimeTimesheet::class);
        $totalStaff = $this->countUniqueEmployees(StaffTimesheet::class);
        $totalUtility = $this->countUniqueEmployees(UtilityTimesheet::class);
        
        // Calculate total unique employees across all timesheet tables
        $totalEmployees = $this->calculateTotalUniqueEmployees();
        
        return [
            'totalEmployees' => $totalEmployees,
            'totalFulltimeInstructors' => $totalFulltimeInstructors,
            'totalParttimeInstructors' => $totalParttimeInstructors,
            'totalStaff' => $totalStaff,
            'totalUtility' => $totalUtility,
        ];
    }

    /**
     * Count unique employees in a timesheet table by name (most reliable method)
     *
     * @param string $modelClass
     * @return int
     */
    private function countUniqueEmployees($modelClass)
    {
        try {
            $tableName = (new $modelClass)->getTable();
            return DB::table($tableName)
                ->distinct()
                ->count('name');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calculate total unique employees across all timesheet tables
     * This prevents double-counting employees who appear in multiple tables
     *
     * @return int
     */
    private function calculateTotalUniqueEmployees()
    {
        try {
            $allNames = collect();
            
            // Get all unique names from each timesheet table
            $tables = [
                (new FulltimeTimesheet)->getTable(),
                (new ParttimeTimesheet)->getTable(),
                (new StaffTimesheet)->getTable(),
                (new UtilityTimesheet)->getTable()
            ];
            
            foreach ($tables as $table) {
                $names = DB::table($table)->distinct()->pluck('employee_name');
                $allNames = $allNames->merge($names);
            }
            
            // Return count of unique names across all tables
            return $allNames->unique()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Send payslips to all employees across all timesheet tables
     */
    public function sendPayslips(Request $request)
    {
        if (!session()->has('user_id') || !session()->get('is_admin')) {
            return back()->with('error', 'Please login as admin first.');
        }

        // Collect recipients: email, name, and total_honorarium from each table
        $recipients = collect();

        $fulltimeTable = (new FulltimeTimesheet)->getTable();
        $parttimeTable = (new ParttimeTimesheet)->getTable();
        $staffTable = (new StaffTimesheet)->getTable();
        $utilityTable = (new UtilityTimesheet)->getTable();

        // Fulltime: has email and period columns
        $fulltimeRows = DB::table($fulltimeTable)
            ->select('employee_name as name', 'email', 'total_honorarium', 'period')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('id')
            ->get();
        foreach ($fulltimeRows as $row) {
            if (!$row->email) continue;
            if (!$recipients->has($row->email)) {
                $recipients->put($row->email, [
                    'name' => $row->name ?: 'Employee',
                    'email' => $row->email,
                    'total' => (float) ($row->total_honorarium ?? 0),
                    'period' => $row->period ?? null,
                    'type' => 'Fulltime',
                ]);
            }
        }

        // Part-time: has email but no period column
        $parttimeRows = DB::table($parttimeTable)
            ->select('employee_name as name', 'email', 'total_honorarium')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('id')
            ->get();
        foreach ($parttimeRows as $row) {
            if (!$row->email) continue;
            if (!$recipients->has($row->email)) {
                $recipients->put($row->email, [
                    'name' => $row->name ?: 'Employee',
                    'email' => $row->email,
                    'total' => (float) ($row->total_honorarium ?? 0),
                    'period' => null,
                    'type' => 'Part-time',
                ]);
            }
        }

        // Staff: has email but no period column
        $staffRows = DB::table($staffTable)
            ->select('employee_name as name', 'email', 'total_honorarium')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderByDesc('id')
            ->get();
        foreach ($staffRows as $row) {
            if (!$row->email) continue;
            if (!$recipients->has($row->email)) {
                $recipients->put($row->email, [
                    'name' => $row->name ?: 'Employee',
                    'email' => $row->email,
                    'total' => (float) ($row->total_honorarium ?? 0),
                    'period' => null,
                    'type' => 'Staff',
                ]);
            }
        }

        // Utility: no email column; use employees.email via employee_id
        $utilityRows = DB::table($utilityTable)
            ->leftJoin('employees as e', $utilityTable . '.employee_id', '=', 'e.id')
            ->select($utilityTable . '.employee_name as name', 'e.email as email', $utilityTable . '.total_honorarium')
            ->whereNotNull('e.email')
            ->where('e.email', '!=', '')
            ->orderByDesc($utilityTable . '.id')
            ->get();
        foreach ($utilityRows as $row) {
            if (!$row->email) continue;
            if (!$recipients->has($row->email)) {
                $recipients->put($row->email, [
                    'name' => $row->name ?: 'Employee',
                    'email' => $row->email,
                    'total' => (float) ($row->total_honorarium ?? 0),
                    'period' => null,
                    'type' => 'Utility',
                ]);
            }
        }

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No employees with emails found to send payslips.');
        }

        $sent = 0; $failed = 0; $errors = [];

        // Create a batch id to group this sending operation
        $batchId = now()->format('YmdHis') . '-' . bin2hex(random_bytes(4));

        foreach ($recipients as $payload) {
            try {
                Mail::to($payload['email'])->send(new PayslipMail(
                    name: $payload['name'],
                    totalHonorarium: $payload['total'],
                    period: $payload['period'],
                    employeeType: $payload['type']
                ));
                $sent++;

                // Log success into payslip_histories
                \DB::table('payslip_histories')->insert([
                    'batch_id' => $batchId,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'employee_type' => $payload['type'],
                    'total_honorarium' => $payload['total'],
                    'period' => $payload['period'],
                    'status' => 'sent',
                    'error' => null,
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = $payload['email'] . ': ' . $e->getMessage();

                // Log failure into payslip_histories
                \DB::table('payslip_histories')->insert([
                    'batch_id' => $batchId,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'employee_type' => $payload['type'],
                    'total_honorarium' => $payload['total'],
                    'period' => $payload['period'],
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $msg = "Payslips sent: {$sent}. Failed: {$failed}. Batch: {$batchId}.";
        if ($failed > 0 && config('app.debug')) {
            $msg .= ' Errors: ' . implode(' | ', $errors);
        }

        return back()->with('success', $msg);
    }
}