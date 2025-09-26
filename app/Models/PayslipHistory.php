<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayslipHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_id', 'name', 'email', 'employee_type', 'total_honorarium', 'period', 'status', 'error', 'sent_at'
    ];
}