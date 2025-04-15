<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_type',
        'employee_type',
        'amount',
        'pay_date',
        'payment_file',
        'user_id',
        'added_by',
        'notes',
    ];
}
