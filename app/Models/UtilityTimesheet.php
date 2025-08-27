<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityTimesheet extends Model
{
    protected $fillable = [
        'employee_id',
        'employee_name',
        'designation',
        'prov_abr',
        'department',
        'days',
        'details',
        'total_days',
        'rate_per_day',
        'deduction',
        'total_honorarium'
    ];

    protected $casts = [
        'days' => 'array', // JSON stored as array
    ];

    /**
     * Get the employee that owns the timesheet
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}