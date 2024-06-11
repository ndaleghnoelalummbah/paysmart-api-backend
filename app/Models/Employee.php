<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'matricule',
        'email',
        'phone',
        'position',
        'employment_date',
        'work_status',
        'hourly_income',
        'housing_allowance',
        'hourly_overtime_pay',
        'department_id',
    ];

    protected $casts = [
        'employment_date' => 'date',
        'hourly_income' => 'double',
        'housing_allowance' => 'double',
        'hourly_overtime_pay' => 'double',
    ];

    /**
     * Get the department that the employee belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the attendances for the employee.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the leaves for the employee.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get the payments for the employee.
     */
    public function employeesPayments()
    {
        return $this->hasMany(EmployeePayment::class);
    }
}
