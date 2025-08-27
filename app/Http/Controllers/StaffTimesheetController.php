<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffTimesheet;

class StaffTimesheetController extends Controller
{
    public function index()
    {
        $timesheets = StaffTimesheet::all();
        return view('staff.index', compact('timesheets'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
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
        
        StaffTimesheet::create($data);
        return redirect()->route('staff.index')->with('success', 'Staff timesheet added!');
    }

    public function edit($id)
    {
        $timesheet = StaffTimesheet::findOrFail($id);
        return view('staff.edit', compact('timesheet'));
    }

    public function update(Request $request, $id)
    {
        $timesheet = StaffTimesheet::findOrFail($id);
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
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
        return redirect()->route('staff.index')->with('success', 'Staff timesheet updated!');
    }

    public function destroy($id)
    {
        $timesheet = StaffTimesheet::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('staff.index')->with('success', 'Staff timesheet deleted!');
    }

    public function printAll()
    {
        $timesheets = StaffTimesheet::all();
        return view('staff.print', compact('timesheets'));
    }
}