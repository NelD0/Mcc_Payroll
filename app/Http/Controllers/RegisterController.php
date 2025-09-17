<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validate form input with enhanced password security
        $validationRules = [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'role' => 'required|in:admin,attendance_checker',
        ];

        // Add course validation only if role is attendance_checker
        if ($request->role === 'attendance_checker') {
            $validationRules['course'] = 'required|in:bsit,bsba,bshm,bsed,beed';
        }

        $request->validate($validationRules, [
            'name.regex' => 'Name can only contain letters and spaces.',
            'password.min' => 'Password must be at least 12 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'role.required' => 'Please select a user role.',
            'role.in' => 'Invalid role selected.',
            'course.required' => 'Please select a department for attendance checker role.',
            'course.in' => 'Invalid department selected.',
        ]);

        // Hash password with Argon2id with enhanced security parameters
        $hashedPassword = password_hash($request->password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost' => 4,       // 4 iterations
            'threads' => 3          // 3 threads
        ]);

        // Prepare data for insertion
        $userData = [
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => $hashedPassword,
            'role'       => $request->role,
            'course'     => $request->role === 'attendance_checker' ? $request->course : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert into database
        DB::table('users')->insert($userData);

        // Create success message based on role
        $roleText = $request->role === 'admin' ? 'Administrator' : 'Attendance Checker';
        $courseText = $request->role === 'attendance_checker' ? ' for ' . strtoupper($request->course) : '';
        
        $successMessage = $roleText . ' account created successfully' . $courseText . '! You can now login.';

        return redirect('/')->with('success', $successMessage);
    }
}
