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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'position' => 'required|in:Instructor,Staff,Utility',
            'hourly_salary' => 'required|numeric',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        Employee::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Employee added successfully.');
    }
}
