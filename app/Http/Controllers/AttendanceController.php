<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function login(Request $request)
    {
        // Check lockout first
        $now = Carbon::now();
        $lockoutUntil = $request->session()->get('attendance_lockout_until');
        if ($lockoutUntil && Carbon::parse($lockoutUntil)->gt($now)) {
            $remaining = Carbon::parse($lockoutUntil)->diffInSeconds($now);
            return back()
                ->with('error', 'Too many attempts. Please wait before trying again.')
                ->with('lockout_remaining', $remaining);
        }

        // If lockout expired, clear it
        if ($lockoutUntil && Carbon::parse($lockoutUntil)->lte($now)) {
            $request->session()->forget('attendance_lockout_until');
            $request->session()->forget('attendance_attempts');
        }

        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:attendance'
        ]);

        // Find attendance user in database
        $attendanceUser = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'attendance_checker')
            ->first();

        if ($attendanceUser && password_verify($request->password, $attendanceUser->password)) {
            // Success: reset attempts/lockout
            $request->session()->forget('attendance_attempts');
            $request->session()->forget('attendance_lockout_until');

            // Additional security: Check if password needs rehashing (for upgraded security)
            if (password_needs_rehash($attendanceUser->password, PASSWORD_ARGON2ID, [
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
                DB::table('users')->where('id', $attendanceUser->id)->update(['password' => $newHash]);
            }
            // Start attendance session
            $request->session()->put('user_id', $attendanceUser->id);
            $request->session()->put('user_name', $attendanceUser->name);
            $request->session()->put('user_role', 'attendance_checker');
            $request->session()->put('user_course', $attendanceUser->course);
            $request->session()->put('is_attendance', true);

            // Mark online in cache with metadata (IP and device)
            $ip = $request->ip();
            $device = substr($request->header('User-Agent', 'unknown'), 0, 120);
            Cache::put('user-is-online-' . $attendanceUser->id, true, now()->addMinutes(10));
            Cache::put('user-online-info-' . $attendanceUser->id, [
                'ip' => $ip,
                'device' => $device,
                'at' => now()->toIso8601String(),
            ], now()->addMinutes(10));

            return redirect('/attendance/dashboard')->with('success', 'Welcome back, ' . $attendanceUser->name . '!');
        }

        // Failed attempt: increment counter and maybe lock
        $attempts = (int) $request->session()->get('attendance_attempts', 0) + 1;
        $request->session()->put('attendance_attempts', $attempts);

        if ($attempts >= 3) {
            // Lock out for 60 seconds and reset attempts
            $lockUntil = $now->copy()->addSeconds(60);
            $request->session()->put('attendance_lockout_until', $lockUntil->toIso8601String());
            $request->session()->forget('attendance_attempts');

            return back()
                ->with('error', 'Invalid credentials. Please wait 60 seconds before trying again.')
                ->with('lockout_remaining', 60);
        }

        return back()
            ->with('error', 'Invalid attendance credentials or you are not authorized for attendance.')
            ->with('attempts_left', max(0, 3 - $attempts));
    }

    public function showForgotForm()
    {
        return view('attendance.forgot_password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Always respond success to prevent user enumeration
        $genericResponse = redirect()->route('attendance.reset.form', ['email' => $request->email])
            ->with('success', 'If the email exists and is authorized, an OTP has been sent.');

        // Verify the email belongs to an attendance checker
        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'attendance_checker')
            ->first();

        if (!$user) {
            return $genericResponse;
        }

        // Generate 6-digit OTP and hash it
        $otp = random_int(100000, 999999);
        $otpHash = password_hash((string) $otp, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);

        // Store in DB with 10-minute expiration
        DB::table('attendance_password_otps')->insert([
            'email' => $request->email,
            'otp_hash' => $otpHash,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send email via Mailable template
        try {
            \Mail::to($request->email)->send(new \App\Mail\AttendanceOtpMail((string) $otp));
        } catch (\Throwable $e) {
            // Log the error for debugging without exposing sensitive data
            Log::error('Failed to send attendance OTP email', [
                'to' => $request->email,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ]);
            // Still return generic response to avoid user enumeration
        }

        return $genericResponse;
    }

    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        return view('attendance.reset_password', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = DB::table('attendance_password_otps')
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('id')
            ->first();

        if (!$otpRecord || !password_verify($request->otp, $otpRecord->otp_hash)) {
            return back()->withInput()->with('error', 'Invalid or expired OTP.');
        }

        // Mark OTP as used and set session flag for verified email
        DB::table('attendance_password_otps')->where('id', $otpRecord->id)->update([
            'used_at' => now(),
            'updated_at' => now(),
        ]);

        $request->session()->put('attendance_reset_verified_email', $request->email);
        $request->session()->put('attendance_reset_verified_until', now()->addMinutes(10)->toIso8601String());

        return redirect()->route('attendance.change.form')->with('success', 'OTP verified. You can now change your password.');
    }

    public function showChangePasswordForm(Request $request)
    {
        $verifiedEmail = $request->session()->get('attendance_reset_verified_email');
        $until = $request->session()->get('attendance_reset_verified_until');

        if (!$verifiedEmail || ($until && \Carbon\Carbon::parse($until)->lte(now()))) {
            return redirect()->route('attendance.forgot.form')->with('error', 'Session expired. Please request a new OTP.');
        }

        return view('attendance.change_password', ['email' => $verifiedEmail]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ensure the email was verified via OTP recently
        $verifiedEmail = $request->session()->get('attendance_reset_verified_email');
        $until = $request->session()->get('attendance_reset_verified_until');
        if ($verifiedEmail !== $request->email || ($until && \Carbon\Carbon::parse($until)->lte(now()))) {
            return redirect()->route('attendance.forgot.form')->with('error', 'OTP verification required or expired. Please request a new code.');
        }

        // Verify user exists and is attendance checker
        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('role', 'attendance_checker')
            ->first();

        if (!$user) {
            return back()->with('error', 'Unable to reset password for this account.');
        }

        // Update password
        $newHash = password_hash($request->password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3,
        ]);
        DB::table('users')->where('id', $user->id)->update([
            'password' => $newHash,
            'updated_at' => now(),
        ]);

        // Clear verification session
        $request->session()->forget('attendance_reset_verified_email');
        $request->session()->forget('attendance_reset_verified_until');

        return redirect()->route('attendance.attendlog.form')->with('success', 'Password updated. You can now log in.');
    }

    public function dashboard(Request $request)
    {
        // Check if user is logged in and is attendance
        if (!session()->has('user_id') || !session()->get('is_attendance')) {
            return redirect('/')->with('error', 'Please login as attendance first.');
        }

        // Return the dedicated attendance dashboard
        return view('attendance.dashboard');
    }

    public function getCourseCounts()
    {
        try {
            // Get the user's course from session
            $userCourse = strtoupper(session()->get('user_course', 'BSIT'));
            
            // Get counts from timesheet tables for the user's department only
            $courseCount = DB::table('fulltime_timesheets')
                ->where('department', $userCourse)
                ->distinct('employee_name')
                ->count() + 
                DB::table('parttime_timesheets')
                ->where('department', $userCourse)
                ->distinct('employee_name')
                ->count();

            return response()->json([
                'course' => strtolower($userCourse),
                'count' => $courseCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'course' => strtolower(session()->get('user_course', 'bsit')),
                'count' => 0
            ]);
        }
    }

    public function getAttendanceData($course, Request $request)
    {
        try {
            $course = strtoupper($course);
            $userCourse = strtoupper(session()->get('user_course', 'BSIT'));
            
            // Security check: ensure user can only access their own course data
            if ($course !== $userCourse) {
                return response()->json([
                    'error' => 'Unauthorized access to course data'
                ], 403);
            }
            
            $weekStart = $request->query('week_start');
            
            // Get fulltime employees from the user's department
            $fulltimeEmployees = DB::table('fulltime_timesheets')
                ->where('department', $course)
                ->select('id', 'employee_name', 'designation', 'employee_type', 'days')
                ->get()
                ->map(function ($employee) {
                    $days = json_decode($employee->days, true) ?? [];
                    return [
                        'id' => 'FT-' . $employee->id,
                        'employee_name' => $employee->employee_name,
                        'designation' => $employee->designation,
                        'employee_type' => $employee->employee_type,
                        'days' => $days
                    ];
                });

            // Get parttime employees from the user's department
            $parttimeEmployees = DB::table('parttime_timesheets')
                ->where('department', $course)
                ->select('id', 'employee_name', 'designation', 'employee_type', 'days')
                ->get()
                ->map(function ($employee) {
                    $days = json_decode($employee->days, true) ?? [];
                    return [
                        'id' => 'PT-' . $employee->id,
                        'employee_name' => $employee->employee_name,
                        'designation' => $employee->designation,
                        'employee_type' => $employee->employee_type,
                        'days' => $days
                    ];
                });

            // Combine both collections
            $allEmployees = $fulltimeEmployees->concat($parttimeEmployees);

            return response()->json($allEmployees);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching attendance data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveAttendance(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'course' => 'required|string',
                'week_start' => 'required|date',
                'attendance_data' => 'required|array'
            ]);

            $course = strtoupper($request->course);
            $userCourse = strtoupper(session()->get('user_course', 'BSIT'));
            
            // Security check: ensure user can only save their own course data
            if ($course !== $userCourse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to course data'
                ], 403);
            }
            
            $weekStart = $request->week_start;
            $attendanceData = $request->attendance_data;

            // For now, we'll store attendance data in the existing timesheet structure
            // by updating the 'days' JSON field with attendance information
            
            foreach ($attendanceData as $attendance) {
                $employeeId = $attendance['id'];
                $attendanceRecord = $attendance['attendance'];
                
                // Determine if this is fulltime or parttime based on ID prefix
                $isFulltime = str_starts_with($employeeId, 'FT-');
                $actualId = str_replace(['FT-', 'PT-'], '', $employeeId);
                
                // Build days array based on attendance
                $days = [
                    'monday' => $attendanceRecord['monday'] ? 8 : 0,
                    'tuesday' => $attendanceRecord['tuesday'] ? 8 : 0,
                    'wednesday' => $attendanceRecord['wednesday'] ? 8 : 0,
                    'thursday' => $attendanceRecord['thursday'] ? 8 : 0,
                    'friday' => $attendanceRecord['friday'] ? 8 : 0,
                    'saturday' => $attendanceRecord['saturday'] ? 8 : 0,
                ];

                if ($isFulltime) {
                    DB::table('fulltime_timesheets')
                        ->where('id', $actualId)
                        ->where('department', $course)
                        ->update([
                            'days' => json_encode($days),
                            'updated_at' => now()
                        ]);
                } else {
                    DB::table('parttime_timesheets')
                        ->where('id', $actualId)
                        ->where('department', $course)
                        ->update([
                            'days' => json_encode($days),
                            'updated_at' => now()
                        ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDeleteAttendance(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'course' => 'required|string',
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'required|string'
            ]);

            $course = strtoupper($request->course);
            $userCourse = strtoupper(session()->get('user_course', 'BSIT'));
            
            // Security check: ensure user can only delete their own course data
            if ($course !== $userCourse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to course data'
                ], 403);
            }
            
            $employeeIds = $request->employee_ids;
            $deletedCount = 0;

            foreach ($employeeIds as $employeeId) {
                // Determine if this is fulltime or parttime based on ID prefix
                $isFulltime = str_starts_with($employeeId, 'FT-');
                $actualId = str_replace(['FT-', 'PT-'], '', $employeeId);
                
                if ($isFulltime) {
                    $deleted = DB::table('fulltime_timesheets')
                        ->where('id', $actualId)
                        ->where('department', $course)
                        ->delete();
                } else {
                    $deleted = DB::table('parttime_timesheets')
                        ->where('id', $actualId)
                        ->where('department', $course)
                        ->delete();
                }
                
                if ($deleted) {
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} employee record(s)",
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting records: ' . $e->getMessage()
            ], 500);
        }
    }

}