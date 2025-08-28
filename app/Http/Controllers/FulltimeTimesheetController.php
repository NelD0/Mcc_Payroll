<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FulltimeTimesheet;
use App\Models\Department;

class FulltimeTimesheetController extends Controller
{
    public function index()
    {
        $timesheets = FulltimeTimesheet::all();
        return view('fulltime.index', compact('timesheets'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('fulltime.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_name' => 'required',
            'email' => 'nullable|email',
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
        
        FulltimeTimesheet::create($data);
        return redirect()->route('fulltime.index')->with('success', 'Timesheet added!');
    }

    public function edit($id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $departments = Department::active()->get();
        return view('fulltime.edit', compact('timesheet', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $data = $request->validate([
            'employee_name' => 'required',
            'email' => 'nullable|email',
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
        return redirect()->route('fulltime.index')->with('success', 'Timesheet updated!');
    }

    public function destroy($id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $timesheet->delete();
        return redirect()->route('fulltime.index')->with('success', 'Timesheet deleted!');
    }

    public function updateDay(Request $request, $id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $day = $request->input('day');
        $hours = $request->input('hours', 0);
        
        // Get current days data
        $days = $timesheet->days;
        if (is_string($days)) {
            $decoded = json_decode($days, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $days = $decoded;
            } else {
                $days = [];
            }
        } elseif (!is_array($days)) {
            $days = [];
        }
        
        // Update the specific day with hours
        $days[$day] = floatval($hours);
        
        // Remove days with 0 hours
        if ($hours == 0) {
            unset($days[$day]);
        }
        
        // Update the timesheet
        $timesheet->days = json_encode($days);
        
        // Recalculate total hours
        $totalHours = array_sum($days);
        $timesheet->total_hour = $totalHours;
        
        // Recalculate total honorarium
        $timesheet->total_honorarium = ($totalHours * $timesheet->rate_per_hour) - $timesheet->deduction;
        if ($timesheet->total_honorarium < 0) {
            $timesheet->total_honorarium = 0;
        }
        
        $timesheet->save();
        
        return response()->json([
            'success' => true,
            'total_hour' => $totalHours,
            'total_honorarium' => number_format($timesheet->total_honorarium, 2)
        ]);
    }

    public function updateField(Request $request, $id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Validate allowed fields
        $allowedFields = ['employee_name', 'designation', 'prov_abr', 'department', 'details', 'rate_per_hour', 'deduction'];
        
        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }
        
        // Update the field
        $timesheet->$field = $value;
        
        // Recalculate total honorarium if rate_per_hour or deduction changed
        if (in_array($field, ['rate_per_hour', 'deduction'])) {
            $timesheet->total_honorarium = ($timesheet->total_hour * $timesheet->rate_per_hour) - $timesheet->deduction;
            if ($timesheet->total_honorarium < 0) {
                $timesheet->total_honorarium = 0;
            }
        }
        
        $timesheet->save();
        
        return response()->json([
            'success' => true,
            'total_honorarium' => number_format($timesheet->total_honorarium, 2)
        ]);
    }

    public function printAll()
    {
        $timesheets = FulltimeTimesheet::all();
        return view('fulltime.print', compact('timesheets'));
    }
}
