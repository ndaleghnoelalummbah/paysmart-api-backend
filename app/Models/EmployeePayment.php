<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'admin_id',
        'payment_id',
        'payment_date',
        'income_tax',
        'total_overtime',
        'total_normal_pay_hours',
        'overtime_pay',
        'net_pay',
        'gross_pay',
        'house_allowance_pay',
        'longevity_allowance_pay',
        'retirement_deduction',
        'leave_pay',
        'retirement_pay',
        'total_pay'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'income_tax' => 'double',
        'total_overtime' => 'integer',
        'total_normal_pay_hours' => 'integer',
        'overtime_pay' => 'double',
        'net_pay' => 'double',
        'gross_pay' => 'double',
        'house_allowance_pay' => 'double',
        'longevity_allowance_pay' => 'double',
        'retirement_deduction' => 'double',
        'leave_pay' => 'double',
        'retirement_pay' => 'double',
    ];

    /**
     * Get the employee associated with the payment.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the admin who authorized the payment.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the payment details.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
