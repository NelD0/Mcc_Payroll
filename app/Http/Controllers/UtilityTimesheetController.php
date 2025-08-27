<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UtilityTimesheet;

class UtilityTimesheetController extends Controller
{
    public function index()
    {
        $timesheets = UtilityTimesheet::all();
        return view('utility.index', compact('timesheets'));
    }

    public function create()
    {
        return view('utility.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
            'days' => 'nullable',
            'total_days' => 'nullable|numeric',
            'rate_per_day' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);
        
        // Handle days field properly
        $daysInput = $request->input('days', []);
        if (empty($daysInput) || $daysInput === null) {
            $data['days'] = json_encode([]);
        } else {
            $data['days'] = json_encode($daysInput);
        }
        
        // Ensure total_days is never null - set to 0 if null or empty
        if (!isset($data['total_days']) || $data['total_days'] === null || $data['total_days'] === '') {
            $data['total_days'] = 0;
        }
        
        // Ensure rate_per_day is never null - set to 0 if null or empty
        if (!isset($data['rate_per_day']) || $data['rate_per_day'] === null || $data['rate_per_day'] === '') {
            $data['rate_per_day'] = 0;
        }
        
        if (!isset($data['deduction']) || $data['deduction'] === null) {
            $data['deduction'] = 0;
        }
        // Calculate total_honorarium as (total_days * rate_per_day) minus deduction
        $data['total_honorarium'] = 0;
        if (!empty($data['total_days']) && !empty($data['rate_per_day'])) {
            $data['total_honorarium'] = ($data['total_days'] * $data['rate_per_day']) - $data['deduction'];
            if ($data['total_honorarium'] < 0) {
                $data['total_honorarium'] = 0;
            }
        }
        UtilityTimesheet::create($data);
        return redirect()->route('utility.index')->with('success', 'Utility timesheet added!');
    }

    public function edit($id)
    {
        $timesheet = UtilityTimesheet::findOrFail($id);
        return view('utility.edit', compact('timesheet'));
    }

    public function update(Request $request, $id)
    {
        $timesheet = UtilityTimesheet::findOrFail($id);
        $data = $request->validate([
            'employee_name' => 'required',
            'designation' => 'required|in:instructor,utility,staff',
            'prov_abr' => 'nullable',
            'days' => 'nullable',
            'total_days' => 'nullable|numeric',
            'rate_per_day' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);
        
        // Handle days field properly
        $daysInput = $request->input('days', []);
        if (empty($daysInput) || $daysInput === null) {
            $data['days'] = json_encode([]);
        } else {
            $data['days'] = json_encode($daysInput);
        }
        
        // Ensure total_days is never null - set to 0 if null or empty
        if (!isset($data['total_days']) || $data['total_days'] === null || $data['total_days'] === '') {
            $data['total_days'] = 0;
        }
        
        // Ensure rate_per_day is never null - set to 0 if null or empty
        if (!isset($data['rate_per_day']) || $data['rate_per_day'] === null || $data['rate_per_day'] === '') {
            $data['rate_per_day'] = 0;
        }
        
        if (!isset($data['deduction']) || $data['deduction'] === null) {
            $data['deduction'] = 0;
        }
        // Calculate total_honorarium as (total_days * rate_per_day) minus deduction
        $data['total_honorarium'] = 0;
        if (!empty($data['total_days']) && !empty($data['rate_per_day'])) {
            $data['total_honorarium'] = ($data['total_days'] * $data['rate_per_day']) - $data['deduction'];
            if ($data['total_honorarium'] < 0) {
                $data['total_honorarium'] = 0;
            }
        }
        $timesheet->update($data);
        return redirect()->route('utility.index')->with('success', 'Utility timesheet updated!');
    }

    public function destroy($id)
    {
        $timesheet = UtilityTimesheet::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('utility.index')->with('success', 'Utility timesheet deleted!');
    }

    public function printAll()
    {
        $timesheets = UtilityTimesheet::all();
        return view('utility.print', compact('timesheets'));
    }
}