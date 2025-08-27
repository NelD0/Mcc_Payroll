<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParttimeTimesheet;
use App\Models\Department;

class ParttimeTimesheetController extends Controller
{
    public function index()
    {
        $timesheets = ParttimeTimesheet::all();
        return view('parttime.index', compact('timesheets'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('parttime.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
            'department' => 'nullable|string',
            'days' => 'nullable',
            'details' => 'nullable',
            'total_hour' => 'nullable|numeric',
            'rate_per_hour' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);
        $data['days'] = json_encode($request->input('days', []));
        
        // Ensure total_hour is never null - set to 0 if null or empty
        if (!isset($data['total_hour']) || $data['total_hour'] === null || $data['total_hour'] === '') {
            $data['total_hour'] = 0;
        }
        
        // Ensure rate_per_hour is never null - set to 0 if null or empty
        if (!isset($data['rate_per_hour']) || $data['rate_per_hour'] === null || $data['rate_per_hour'] === '') {
            $data['rate_per_hour'] = 0;
        }
        
        // Ensure deduction is never null - set to 0 if null or empty
        if (!isset($data['deduction']) || $data['deduction'] === null || $data['deduction'] === '') {
            $data['deduction'] = 0;
        }
        
        // Calculate total_honorarium as (total_hour * rate_per_hour) minus deduction
        $data['total_honorarium'] = ($data['total_hour'] * $data['rate_per_hour']) - $data['deduction'];
        if ($data['total_honorarium'] < 0) {
            $data['total_honorarium'] = 0;
        }
        
        ParttimeTimesheet::create($data);
        return redirect()->route('parttime.index')->with('success', 'Part-time timesheet added!');
    }

    public function edit($id)
    {
        $timesheet = ParttimeTimesheet::findOrFail($id);
        $departments = Department::active()->get();
        return view('parttime.edit', compact('timesheet', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $timesheet = ParttimeTimesheet::findOrFail($id);
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
            'department' => 'nullable|string',
            'days' => 'nullable',
            'details' => 'nullable',
            'total_hour' => 'nullable|numeric',
            'rate_per_hour' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);
        $data['days'] = json_encode($request->input('days', []));
        
        // Ensure total_hour is never null - set to 0 if null or empty
        if (!isset($data['total_hour']) || $data['total_hour'] === null || $data['total_hour'] === '') {
            $data['total_hour'] = 0;
        }
        
        // Ensure rate_per_hour is never null - set to 0 if null or empty
        if (!isset($data['rate_per_hour']) || $data['rate_per_hour'] === null || $data['rate_per_hour'] === '') {
            $data['rate_per_hour'] = 0;
        }
        
        // Ensure deduction is never null - set to 0 if null or empty
        if (!isset($data['deduction']) || $data['deduction'] === null || $data['deduction'] === '') {
            $data['deduction'] = 0;
        }
        
        // Calculate total_honorarium as (total_hour * rate_per_hour) minus deduction
        $data['total_honorarium'] = ($data['total_hour'] * $data['rate_per_hour']) - $data['deduction'];
        if ($data['total_honorarium'] < 0) {
            $data['total_honorarium'] = 0;
        }
        
        $timesheet->update($data);
        return redirect()->route('parttime.index')->with('success', 'Part-time timesheet updated!');
    }

    public function destroy($id)
    {
        $timesheet = ParttimeTimesheet::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('parttime.index')->with('success', 'Part-time timesheet deleted!');
    }

    public function printAll()
    {
        $timesheets = ParttimeTimesheet::all();
        return view('parttime.print', compact('timesheets'));
    }
}