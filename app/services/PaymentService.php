<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Leave;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Employee;
use App\Mail\PayslipMail;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\EmployeePayment;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\PaymentIntent;

class PaymentService
{
    public function initiatePayment(Admin $admin)
    {
        $employees = Employee::all();
        $settings = Setting::first();
        $payment = new Payment();
        $payment->admin_id = $admin->id;
        $payment->payment_date = null;
        $payment->is_effected = false;
        $payment->payslip_issue_date = now();
        $payment->save();

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)->whereMonth('work_date', now()->month)->get();

            $totalDaysWorked = $attendances->where('status', 'present')->count('normal_pay_hours');
            $totalSickRest = $attendances->where('status', 'sick')->count('status');
            $totalHolidays = $attendances->where('status', 'holiday')->count('status');
            $totalAbsence = $attendances->where('status', 'absent')->count('status');

            $department = Department::where('id', $employee->department_id)->first();
            $normalPayHours = $attendances->sum('normal_pay_hours');
            $totalOvertime = $attendances->sum('overtime_hour');
            logger('department', [$department]);
            if($normalPayHours > 0)
            {
                $netPay = ($normalPayHours * $employee->hourly_income) + ($totalOvertime * $employee->hourly_overtime_pay);
                $housingAllowancePay = $employee->housing_allowance;
                $work_years = $employee->employment_date->diffInYears(now());
                $longevityAllowancePay = ($work_years >=   $settings->minimum_seniority_age) ? $netPay * ($settings->longevity_bonus * $work_years): 0;
                 logger('hey 04', [ $work_years, $longevityAllowancePay, $netPay , $settings->income_tax_rate, $settings->longevity_bonus ,$netPay *  ($settings->longevity_bonus * $work_years)]);
                $incomeTax = $netPay * $settings->income_tax_rate;
                $retirementDeduction = $netPay * $settings->retirement_contribution_rate;

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
                    $employeePayment->total_normal_pay_hours = $normalPayHours;
                    $employeePayment->overtime_pay = $totalOvertime * $employee->hourly_overtime_pay;
                    $employeePayment->net_pay = $netPay;
                    $employeePayment->gross_pay = $grossPay;
                    $employeePayment->house_allowance_pay = $housingAllowancePay;
                    $employeePayment->longevity_allowance_pay = $longevityAllowancePay;
                    $employeePayment->retirement_deduction = $retirementDeduction;
                    $employeePayment->leave_pay = $leavePay;
                    $employeePayment->retirement_pay = $retirementPay;
                
            //    $this->sendPayslip($employee, $employeePayment, $attendances, $department, $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence );
                $employeePayment->save();
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

        // Check if the leave starts the following day or if the leave start date is before the current date but in the same month
        if ($leaveStartDate->isSameDay($currentDate->copy()->addDay()) || 
            ($leaveStartDate->year == $currentYear && 
            $leaveStartDate->month == $currentMonth && 
            $leaveStartDate->lessThan($currentDate))) {
            
            // Include leave pay this month
            $leavePay = $leave->leave_pay;

            // Update the is_paid value on the leave table
            $leave->is_paid = true;
            $leave->save();

            return $leavePay;
        } else {
            // Leave starts in a future month or after the 1st day of the next month. Leave pay will be included next month
            return 0;
        }
        }
        
    }

    protected function sendPayslip(Employee $employee, EmployeePayment $employeePayment, $attendances, $department,  $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence )
    {
    
        Mail::to($employee->email)->send(new PayslipMail($employee, $employeePayment, $attendances, $department,  $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence ));
    }

    public function makePayment( $payment, $admin)
    {
        $employeePayments = EmployeePayment::where('payment_id', $payment->id)->get();
            foreach ($employeePayments as $employeePayment) {
                $this->disburseFunds($employeePayment);
            }

        $payment->update([
            'admin_id' => $admin->id,
            'payment_date' => now(),
            'is_effected' => true,
        ]);
    }

     protected function disburseFunds($employeePayment)
    {
        // Logic to disburse funds to employee's phone account
        $employee =  Employee::where('id', $employeePayment->employee_id )->first();
        $grossPay = $employeePayment->gross_pay;

        /**
         * using payment api to disburse fund
         */
        Stripe::setApiKey(config('services.stripe.secret'));

        if (!$employeePayment->stripe_customer_id) {
            // Create Stripe customer if not exists

            $customer = Customer::create([
               // 'object' => 'employee',
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee ->phone,
               // 'amount' => $grossPay,
                'description' => $employee->name . ' stripe payment details ',
            ]);

            $employee->stripe_customer_id = $customer->id;
            $employee->save();
            logger('hey', [$employee]);




            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => floor($grossPay), // Amount in the smallest currency unit
                    'currency' => 'xaf', // Ensure this is a supported currency by Stripe
                    'customer' => $employee->stripe_customer_id,
                    'payment_method' => 'pm_card_visa', // Replace with a valid payment method
                    'off_session' => true, // Indicates the payment is processed without customer interaction
                    'confirm' => true, // Automatically confirm the PaymentIntent after creation
                    'description' => 'Salary Payment for ' . $employee->name,
                ]);

                // Optionally, handle the PaymentIntent status
                if ($paymentIntent->status == 'succeeded') {
                    // Payment was successful
                    logger('Payment succeeded for ' . $employee->name, [$paymentIntent]);
                } else {
                    // Handle other statuses
                    logger('Payment for ' . $employee->name . ' has status: ' . $paymentIntent->status, [$paymentIntent]);
                }
            } catch (\Exception $e) {
                // Handle error (log it, notify admin, etc.)
                logger('Failed to create PaymentIntent for ' . $employee->name, ['error' => $e->getMessage()]);
            }
        }
               // Charge the customer
        // try {
        //     $charge = Charge::create([
        //         'amount' => floor($grossPay),
        //         'currency' => 'xaf',
        //         'source' => 'tok_visa',
        //         'customer' => $employee->stripe_customer_id,
        //         'description' => 'Salary Payment for ' . $employee->name,
        //     ]);

        // } catch (\Exception $e) {
        //     // Handle error (log it, notify admin, etc.)
        //     // You might want to throw an exception or return an error response here
        // }
    }
}

