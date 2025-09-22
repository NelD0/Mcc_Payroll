<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulltimeTimesheet extends Model
{
    protected $fillable = [
        'employee_id',
        'employee_name',
        'email',
        'designation',
        'prov_abr',
        'department',
        'period',
        'working_days',
        'day1_hours',
        'day2_hours',
        'day3_hours',
        'day4_hours',
        'day5_hours',
        'day6_hours',
        'day7_hours',
        'day8_hours',
        'day9_hours',
        'day10_hours',
        'day11_hours',
        'day12_hours',
        'day13_hours',
        'day14_hours',
        'day15_hours',
        'mon_hours',
        'tue_hours',
        'wed_hours',
        'thu_hours',
        'fri_hours',
        'sat_hours',
        'sun_hours',
        'details',
        'total_hour',
        'rate_per_hour',
        'deduction',
        'total_honorarium'
    ];



    /**
     * Get the employee that owns the timesheet
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}