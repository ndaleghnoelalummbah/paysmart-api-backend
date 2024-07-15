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

        $cnps_contribution = 0;
        $total_income_tax = 0;
        $gross_pay = 0;
        $gross_pay_with_cnps = 0;

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)->whereMonth('work_date', now()->month)->get();

            $totalDaysWorked = $attendances->where('status', 'present')->count('normal_pay_hours');
            $totalSickRest = $attendances->where('status', 'sick')->count('status');
            $totalHolidays = $attendances->where('status', 'holiday')->count('status');
            $totalAbsence = $attendances->where('status', 'absent')->count('status');

            $department = Department::where('id', $employee->department_id)->first();
            $normalPayHours = $attendances->sum('normal_pay_hours');
            $totalOvertime = $attendances->sum('overtime_hour');
            $overtimePay = $attendances->sum(function ($attendance) {
                return $attendance->employee->hourly_income * $attendance->overtime_hour * $attendance->overtime_rate;
            });
            logger('department', [$department]);
            if($normalPayHours > 0)
            {
                $netIncome = ($normalPayHours * $employee->hourly_income) + ($overtimePay);

                $housingAllowancePay = $employee->housing_allowance;
                $work_years = $employee->employment_date->diffInYears(now());
                $longevityAllowancePay = ($work_years >=   $settings->minimum_seniority_age) ? $netIncome * ($settings->longevity_bonus * $work_years): 0;
               
                // Calculate leave pay
                $leavePay = $this->calculateLeavePay($employee);
                
              //  $retirementPay = ($employee->age >= $settings->retirement_age) ? $netIncome * $settings->retirement_contribution_rate / 100 : 0;
                $grossPay = $netIncome + $housingAllowancePay +  $longevityAllowancePay +  $leavePay;

                // Social Security Contributions
                $employeeCnpsContribution = $grossPay * ($settings->housing_loan_fund_employee_rate + $settings->pension_disability_employee_rate);
                $employerCnpsContribution = $grossPay * ($settings->housing_loan_fund_employer_rate + $settings->nef_employer_rate + $settings->family_allowances_employer_rate + $settings->pension_disability_employer_rate + $settings->work_related_accident_employer_rate);

                // Calculate net income before tax
                $netIncomeBeforeTax = $grossPay - $employeeCnpsContribution;

                // Calculate annual taxable income
                $annualNetIncomeBeforeFixedDeduction = $netIncomeBeforeTax * 12;
                $taxableAnnualIncome = $annualNetIncomeBeforeFixedDeduction - $settings->fixed_deduction_amount;

                $annualTax = 0;
                // Calculate annual tax based on tax brackets
                if($taxableAnnualIncome < ($settings->minimum_salary_for_tax*12)){
                    $annualTax = 0;
                    logger('taxableAnnualIncome', [$annualTax, $taxableAnnualIncome, $settings->minimum_salary_for_tax * 12]);

                }else{
                    if ($taxableAnnualIncome <= 2000000) {
                        $annualTax = $taxableAnnualIncome * $settings->tax_rate_1;
                        logger('monthlyIncometax in rate 1', [$annualTax]);
                    } elseif ($taxableAnnualIncome <= 3000000) {
                        $annualTax =
                        $taxableAnnualIncome * $settings->tax_rate_2;
                        logger('monthlyIncometax in rate 2', [$annualTax]);
                    } elseif ($taxableAnnualIncome <= 5000000) {
                        $annualTax =
                        $taxableAnnualIncome * $settings->tax_rate_3;
                        logger('monthlyIncometax in rate 3', [$annualTax]);
                    } else {
                        $annualTax = $taxableAnnualIncome * $settings->tax_rate_4;
                        logger('monthlyIncometax in rate 3', [$annualTax]);
                    }
                }
                

                $monthlyIncomeTax = $annualTax / 12;
                logger('monthlyIncometax', [$monthlyIncomeTax, $annualTax]);

                // Calculate net pay after tax
                $netPay = $netIncomeBeforeTax - $monthlyIncomeTax;

               
                logger('hey 04', [$work_years, $longevityAllowancePay, $netPay, $settings->income_tax_rate, $settings->longevity_bonus, $netPay *  ($settings->longevity_bonus * $work_years)]);

                $employeePayment = new EmployeePayment();
                    $employeePayment->employee_id = $employee->id;
                    $employeePayment->admin_id = $admin->id;
                    $employeePayment->payment_id = $payment->id;
                    $employeePayment->total_overtime = $totalOvertime;
                    $employeePayment->total_normal_pay_hours = $normalPayHours;
                    $employeePayment->overtime_pay = $overtimePay;
                    $employeePayment->house_allowance_pay = ROUND($housingAllowancePay, 3);
                    $employeePayment->longevity_allowance_pay = ROUND($longevityAllowancePay, 3);
                    $employeePayment->leave_pay = ROUND($leavePay, 3);
                    $employeePayment->gross_pay = ROUND($grossPay, 3);
                    $employeePayment->income_tax = ROUND($monthlyIncomeTax, 3);
                    $employeePayment->employee_cnps_contribution = ROUND($employeeCnpsContribution, 3);
                    $employeePayment->employer_cnps_contribution = ROUND($employerCnpsContribution, 3);
                    $employeePayment->net_pay = ROUND($netPay, 3);
                    
            //    $this->sendPayslip($employee, $employeePayment, $attendances, $department, $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence );
                $employeePayment->save();
            } 

            $cnps_contribution += $employeeCnpsContribution + $employerCnpsContribution;
            $total_income_tax += $monthlyIncomeTax;
            $gross_pay += $grossPay;
            $gross_pay_with_cnps +=  $grossPay + $employerCnpsContribution;
           
        }

        $payment->update([
            'total_income_tax' => $total_income_tax,
            'total_cnps_contribution' =>  $cnps_contribution,
            'total_pay' => $gross_pay,
            'total_pay_with_cnps' => $gross_pay_with_cnps
        ]);

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

