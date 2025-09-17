<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    public function login(Request $request)
    {
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

            return redirect('/attendance/dashboard')->with('success', 'Welcome back, ' . $attendanceUser->name . '!');
        }

        // If login fails
        return back()->with('error', 'Invalid attendance credentials or you are not authorized for attendance.');
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