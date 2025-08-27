<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\FulltimeTimesheet;
use App\Models\ParttimeTimesheet;
use App\Models\StaffTimesheet;
use App\Models\UtilityTimesheet;

class AdminController extends Controller
{
    public function login(Request $request)
    {
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
            // Start admin session
            $request->session()->put('user_id', $admin->id);
            $request->session()->put('user_name', $admin->name);
            $request->session()->put('user_role', 'admin');
            $request->session()->put('is_admin', true);

            return redirect('/dashboard')->with('success', 'Welcome back, Admin ' . $admin->name . '!');
        }

        // If login fails
        return back()->with('error', 'Invalid admin credentials or you are not authorized as admin.');
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
                $names = DB::table($table)->distinct()->pluck('name');
                $allNames = $allNames->merge($names);
            }
            
            // Return count of unique names across all tables
            return $allNames->unique()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}