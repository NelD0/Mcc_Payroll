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
            'department' => 'required|string|in:BSIT,BSBA,BSHM,BSED,BEED',
            'period' => 'required|in:1-15,16-30',
            'day1_hours' => 'nullable|numeric|min:0',
            'day2_hours' => 'nullable|numeric|min:0',
            'day3_hours' => 'nullable|numeric|min:0',
            'day4_hours' => 'nullable|numeric|min:0',
            'day5_hours' => 'nullable|numeric|min:0',
            'day6_hours' => 'nullable|numeric|min:0',
            'day7_hours' => 'nullable|numeric|min:0',
            'day8_hours' => 'nullable|numeric|min:0',
            'day9_hours' => 'nullable|numeric|min:0',
            'day10_hours' => 'nullable|numeric|min:0',
            'day11_hours' => 'nullable|numeric|min:0',
            'day12_hours' => 'nullable|numeric|min:0',
            'day13_hours' => 'nullable|numeric|min:0',
            'day14_hours' => 'nullable|numeric|min:0',
            'day15_hours' => 'nullable|numeric|min:0',
            'mon_hours' => 'nullable|numeric|min:0',
            'tue_hours' => 'nullable|numeric|min:0',
            'wed_hours' => 'nullable|numeric|min:0',
            'thu_hours' => 'nullable|numeric|min:0',
            'fri_hours' => 'nullable|numeric|min:0',
            'sat_hours' => 'nullable|numeric|min:0',
            'sun_hours' => 'nullable|numeric|min:0',
            'number_of_days' => 'nullable|integer|min:1|max:15',
            'details' => 'nullable',
            'total_hour' => 'nullable|numeric',
            'rate_per_hour' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);

        // Ensure rate_per_hour is never null - set to 0 if null or empty
        if (!isset($data['rate_per_hour']) || $data['rate_per_hour'] === null || $data['rate_per_hour'] === '') {
            $data['rate_per_hour'] = 0;
        }

        // Ensure deduction is never null - set to 0 if null or empty
        if (!isset($data['deduction']) || $data['deduction'] === null || $data['deduction'] === '') {
            $data['deduction'] = 0;
        }

        // Set default values for all hours if null
        $dayHours = ['day1_hours', 'day2_hours', 'day3_hours', 'day4_hours', 'day5_hours', 'day6_hours', 'day7_hours', 'day8_hours', 'day9_hours', 'day10_hours', 'day11_hours', 'day12_hours', 'day13_hours', 'day14_hours', 'day15_hours'];
        $weeklyHours = ['mon_hours', 'tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours', 'sun_hours'];

        foreach ($dayHours as $hour) {
            if (!isset($data[$hour]) || $data[$hour] === null || $data[$hour] === '') {
                $data[$hour] = 0;
            }
        }

        foreach ($weeklyHours as $hour) {
            if (!isset($data[$hour]) || $data[$hour] === null || $data[$hour] === '') {
                $data[$hour] = 0;
            }
        }

        // Calculate total_hour - prefer day hours if any are set, otherwise use weekly
        $totalFromDays = 0;
        $hasDayHours = false;
        foreach ($dayHours as $hour) {
            if ($data[$hour] > 0) {
                $totalFromDays += $data[$hour];
                $hasDayHours = true;
            }
        }

        if ($hasDayHours) {
            $data['total_hour'] = $totalFromDays;
        } else {
            $data['total_hour'] = $data['mon_hours'] + $data['tue_hours'] + $data['wed_hours'] + $data['thu_hours'] + $data['fri_hours'] + $data['sat_hours'] + $data['sun_hours'];
        }

        // Set working_days and number_of_days
        if ($hasDayHours) {
            $workingDaysCount = 0;
            foreach ($dayHours as $hour) {
                if ($data[$hour] > 0) {
                    $workingDaysCount++;
                }
            }
            $data['number_of_days'] = $workingDaysCount;
            $data['working_days'] = json_encode(['days' => $workingDaysCount]);
        } else {
            $workingDays = [];
            if ($data['mon_hours'] > 0) $workingDays[] = 'mon';
            if ($data['tue_hours'] > 0) $workingDays[] = 'tue';
            if ($data['wed_hours'] > 0) $workingDays[] = 'wed';
            if ($data['thu_hours'] > 0) $workingDays[] = 'thu';
            if ($data['fri_hours'] > 0) $workingDays[] = 'fri';
            if ($data['sat_hours'] > 0) $workingDays[] = 'sat';
            if ($data['sun_hours'] > 0) $workingDays[] = 'sun';
            $data['working_days'] = json_encode($workingDays);
            $data['number_of_days'] = count($workingDays);
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
            'department' => 'required|string|in:BSIT,BSBA,BSHM,BSED,BEED',
            'period' => 'required|in:1-15,16-30',
            'mon_hours' => 'nullable|numeric|min:0',
            'tue_hours' => 'nullable|numeric|min:0',
            'wed_hours' => 'nullable|numeric|min:0',
            'thu_hours' => 'nullable|numeric|min:0',
            'fri_hours' => 'nullable|numeric|min:0',
            'sat_hours' => 'nullable|numeric|min:0',
            'sun_hours' => 'nullable|numeric|min:0',
            'number_of_days' => 'nullable|integer|min:1|max:7',
            'details' => 'nullable',
            'total_hour' => 'nullable|numeric',
            'rate_per_hour' => 'nullable|numeric',
            'deduction' => 'nullable|numeric',
        ]);

        // Ensure rate_per_hour is never null - set to 0 if null or empty
        if (!isset($data['rate_per_hour']) || $data['rate_per_hour'] === null || $data['rate_per_hour'] === '') {
            $data['rate_per_hour'] = 0;
        }

        // Ensure deduction is never null - set to 0 if null or empty
        if (!isset($data['deduction']) || $data['deduction'] === null || $data['deduction'] === '') {
            $data['deduction'] = 0;
        }

        // Set default values for hours if null
        $hours = ['mon_hours', 'tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours', 'sun_hours'];
        foreach ($hours as $hour) {
            if (!isset($data[$hour]) || $data[$hour] === null || $data[$hour] === '') {
                $data[$hour] = 0;
            }
        }

        // Calculate total_hour as sum of all day hours
        $data['total_hour'] = $data['mon_hours'] + $data['tue_hours'] + $data['wed_hours'] + $data['thu_hours'] + $data['fri_hours'] + $data['sat_hours'] + $data['sun_hours'];

        // Set working_days as JSON array of days with hours > 0
        $workingDays = [];
        if ($data['mon_hours'] > 0) $workingDays[] = 'mon';
        if ($data['tue_hours'] > 0) $workingDays[] = 'tue';
        if ($data['wed_hours'] > 0) $workingDays[] = 'wed';
        if ($data['thu_hours'] > 0) $workingDays[] = 'thu';
        if ($data['fri_hours'] > 0) $workingDays[] = 'fri';
        if ($data['sat_hours'] > 0) $workingDays[] = 'sat';
        if ($data['sun_hours'] > 0) $workingDays[] = 'sun';
        $data['working_days'] = json_encode($workingDays);

        // Set number_of_days if not set
        if (!isset($data['number_of_days']) || $data['number_of_days'] === null) {
            $data['number_of_days'] = count($workingDays);
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

    // days functionality removed
    public function updateDay(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Days functionality has been removed.'
        ], 410);
    }

    public function updateField(Request $request, $id)
    {
        $timesheet = FulltimeTimesheet::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Validate allowed fields
        $allowedFields = ['employee_name', 'designation', 'prov_abr', 'department', 'period', 'day1_hours', 'day2_hours', 'day3_hours', 'day4_hours', 'day5_hours', 'day6_hours', 'day7_hours', 'day8_hours', 'day9_hours', 'day10_hours', 'day11_hours', 'day12_hours', 'day13_hours', 'day14_hours', 'day15_hours', 'mon_hours', 'tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours', 'sun_hours', 'number_of_days', 'details', 'rate_per_hour', 'deduction'];
        
        if (!in_array($field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }
        
        // Update the field
        $timesheet->$field = $value;
        
        // Recalculate total_hour if day hours changed
        $dayFields = ['day1_hours', 'day2_hours', 'day3_hours', 'day4_hours', 'day5_hours', 'day6_hours', 'day7_hours', 'day8_hours', 'day9_hours', 'day10_hours', 'day11_hours', 'day12_hours', 'day13_hours', 'day14_hours', 'day15_hours'];
        $weeklyFields = ['mon_hours', 'tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours', 'sun_hours'];

        if (in_array($field, $dayFields) || in_array($field, $weeklyFields)) {
            // Calculate total from individual day hours if any day field exists, otherwise use weekly
            $totalFromDays = 0;
            $hasDayFields = false;
            for ($i = 1; $i <= 15; $i++) {
                $dayField = "day{$i}_hours";
                if (isset($timesheet->$dayField) && $timesheet->$dayField > 0) {
                    $totalFromDays += $timesheet->$dayField;
                    $hasDayFields = true;
                }
            }

            if ($hasDayFields) {
                $timesheet->total_hour = $totalFromDays;
            } else {
                $timesheet->total_hour = $timesheet->mon_hours + $timesheet->tue_hours + $timesheet->wed_hours + $timesheet->thu_hours + $timesheet->fri_hours + $timesheet->sat_hours + $timesheet->sun_hours;
            }

            // Update working_days and number_of_days
            if ($hasDayFields) {
                $workingDaysCount = 0;
                for ($i = 1; $i <= 15; $i++) {
                    $dayField = "day{$i}_hours";
                    if (isset($timesheet->$dayField) && $timesheet->$dayField > 0) {
                        $workingDaysCount++;
                    }
                }
                $timesheet->number_of_days = $workingDaysCount;
                $timesheet->working_days = json_encode(['days' => $workingDaysCount]); // Simplified
            } else {
                $workingDays = [];
                if ($timesheet->mon_hours > 0) $workingDays[] = 'mon';
                if ($timesheet->tue_hours > 0) $workingDays[] = 'tue';
                if ($timesheet->wed_hours > 0) $workingDays[] = 'wed';
                if ($timesheet->thu_hours > 0) $workingDays[] = 'thu';
                if ($timesheet->fri_hours > 0) $workingDays[] = 'fri';
                if ($timesheet->sat_hours > 0) $workingDays[] = 'sat';
                if ($timesheet->sun_hours > 0) $workingDays[] = 'sun';
                $timesheet->working_days = json_encode($workingDays);
                $timesheet->number_of_days = count($workingDays);
            }
        }

        // Recalculate total honorarium if rate_per_hour, deduction, or any hours changed
        if (in_array($field, ['rate_per_hour', 'deduction']) || in_array($field, $dayFields) || in_array($field, $weeklyFields)) {
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
