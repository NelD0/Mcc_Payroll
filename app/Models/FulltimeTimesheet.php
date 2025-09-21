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