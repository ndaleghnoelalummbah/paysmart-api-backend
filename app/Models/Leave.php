<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'resumption_date',
        'is_paid',
        'leave_pay',
    ];

    protected $casts = [
        'start_date' => 'date',
        'resumption_date' => 'date',
        'is_paid' => 'boolean',
        'leave_pay' => 'double',
    ];

    /**
     * Get the employee that owns the leave.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
