<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\EmployeePayment;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayslipMail;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function initiatePayment($admin)
    {
        $employees = Employee::all();
        $settings = Setting::first();
        $payment = new Payment();
       // $department = Department::all();
   logger('Number of employees:', [$employees->count()]);
        $payment->admin_id = $admin->id;
        $payment->payment_date = null;
        $payment->is_affected = false;
        $payment->payslip_issue_date = now();
        $payment->save();
        logger('hey 3', [$settings]);

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)->whereMonth('work_date', now()->month)->get();
            $department = Department::where('id', $employee->department_id)->first();
            $regularPayHours = $attendances->sum('hours_worked');
            $totalOvertime = $attendances->sum('overtime_hour');
logger('department', [$department]);
            if($regularPayHours > 0)
            {
                $netPay = ($regularPayHours * $employee->hourly_income) + ($totalOvertime * $employee->hourly_overtime_pay);
                $housingAllowancePay = $employee->housing_allowance;
                $work_years = $employee->employment_date->diffInYears(now());
                // logger('hey 3', [$work_years]);
                $longevityAllowancePay = ($work_years >=   $settings->minimum_seniority_age) ? $netPay * ($settings->longevity_bonus * $work_years): 0;
                 logger('hey 3', [ $work_years, $longevityAllowancePay, $netPay , $settings->income_tax_rate, $settings->longevity_bonus ,$netPay *  ($settings->longevity_bonus * $work_years)]);
                $incomeTax = $netPay * $settings->income_tax_rate;
                $retirementDeduction = $netPay * $settings->retirement_contribution_rate;
            // $leavePay = $employee->leave_pay ?? 0;


                // Calculate leave pay
                $leavePay = $this->calculateLeavePay($employee);

                $retirementPay = ($employee->age >= $settings->retirement_age) ? $netPay * $settings->retirement_contribution_rate / 100 : 0;
                $grossPay = $netPay 
                        - $incomeTax 
                        - $retirementDeduction 
                        + $housingAllowancePay 
                        + $longevityAllowancePay 
                        + $leavePay 
                        + $retirementPay;

                $employeePayment = new EmployeePayment();
                    $employeePayment->employee_id = $employee->id;
                    $employeePayment->admin_id = $admin->id;
                    $employeePayment->payment_id = $payment->id;
                    $employeePayment->payment_date = null;
                    $employeePayment->income_tax = $incomeTax;
                    $employeePayment->total_overtime = $totalOvertime;
                    $employeePayment->total_hours_worked = $regularPayHours;
                    $employeePayment->overtime_pay = $totalOvertime * $employee->hourly_overtime_pay;
                    $employeePayment->net_pay = $netPay;
                    $employeePayment->gross_pay = $grossPay;
                    $employeePayment->house_allowance_pay = $housingAllowancePay;
                    $employeePayment->longevity_allowance_pay = $longevityAllowancePay;
                    $employeePayment->retirement_deduction = $retirementDeduction;
                    $employeePayment->leave_pay = $leavePay;
                    $employeePayment->retirement_pay = $retirementPay;
                
                $this->sendPayslip($employee, $employeePayment, $attendances, $department);
                $employeePayment->save();
    logger('hey 4');
            }

           
        }

        return $payment;
    }

    protected function calculateLeavePay($employee)
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;

        // Get the relevant leave entry
        $leave = Leave::where('employee_id', $employee->id)
            ->where('resumption_date', '>', $currentDate)
            ->where('is_paid', false)
            ->orderBy('start_date')
            ->first();

        if ($leave) {
            $leaveStartDate = Carbon::parse($leave->start_date);

            // Check if the leave starts in a future month or after the 1st day
            if (($leaveStartDate->year = $currentYear ) && (($leaveStartDate->month =  $currentMonth && $leaveStartDate->day > 1) || ($leaveStartDate->month >  $currentMonth && $leaveStartDate->day = 1 && $leaveStartDate->month -  $currentMonth  = 1))) {
                // Leave starts in the current month or in a future month but on the 1st day
                // Include leave pay this month
                $leavePay = $leave->leave_pay;

                // Update the is_paid value on the leave table
                $leave->is_paid = true;
                $leave->save();

                return $leavePay;
               
            } else { 
                // Leave starts in a future month and after the 1st day
                // Leave pay will be included next month
                return 0;
                
            }
        }

        return 0;
    }

    protected function sendPayslip($employee, $employeePayment, $attendances, $department)
    {
    
        Mail::to($employee->email)->send(new PayslipMail($employee, $employeePayment, $attendances, $department));
    }

    public function makePayment( $payment, $admin)
    {
         $payment->update([
            'admin_id' => $admin->id,
            'payment_date' => now(),
            'is_affected' => true,
        ]);

        $employeePayments = EmployeePayment::where('payment_id', $payment->id)->get();
            foreach ($employeePayments as $employeePayment) {
                $this->disburseFunds($employeePayment);
            }
    }

     protected function disburseFunds($employeePayment)
    {
        // Logic to disburse funds to employee's phone account
        $employee =  Employee::where('id', $employeePayment->employee_id )->get();
        $phone = $employee->phone;
        $grossPay = $employeePayment->gross_pay;

        /**
         * using payment api to disburse fund
         */
    }
}

