<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'work_date',
        'status',
        'normal_pay_hours',
        'overtime_hour',
    ];

    protected $casts = [
        'work_date' => 'date',
        'normal_pay_hours' => 'integer',
        'overtime_hour' => 'integer',
    ];

    /**
     * Get the employee associated with the attendance record.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
