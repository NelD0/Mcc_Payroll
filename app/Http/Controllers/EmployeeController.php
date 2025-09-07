<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('dashboard', compact('employees'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'type' => 'required|in:Full-time Instructor,Part-time Instructor,Staff,Utility',
            'hourly_salary' => 'required|numeric',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Map new field names to existing columns if needed
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'position' => $data['type'] === 'Full-time Instructor' ? 'Instructor' : ($data['type'] === 'Part-time Instructor' ? 'Instructor' : $data['type']),
            'hourly_salary' => $data['hourly_salary'],
            'department_id' => $data['department_id'] ?? null,
        ];

        $employee = Employee::create($payload);

        // If full-time, also create an initial fulltime record so it appears in fulltime list
        if ($data['type'] === 'Full-time Instructor') {
            \App\Models\FulltimeTimesheet::create([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'email' => $employee->email,
                'designation' => 'instructor',
                'prov_abr' => null,
                'department' => 'BSIT', // default; can be updated later or mapped if needed
                'days' => json_encode([]),
                'details' => null,
                'total_hour' => 0,
                'rate_per_hour' => $employee->hourly_salary ?? 0,
                'deduction' => 0,
                'total_honorarium' => 0,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Employee added successfully.');
    }
}
