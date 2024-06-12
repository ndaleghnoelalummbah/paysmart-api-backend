<?php

namespace App\Http\Controllers;

use App\Models\EmployeePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployeePaymentResource;
use App\Http\Resources\YearlyEmployeePaymentSummaryResource;
use App\Http\Resources\MostRecentEmployeePaymentSummaryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeePaymentController extends Controller
{
    /**
     * table for employee yearly payslip
     */
     public function show(Request $request, $id)
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        $employeePayments = EmployeePayment::with(['payment'])
         ->whereHas('payment', function ($query) use ($currentYear) {
            $query->whereYear('payment_date', $currentYear);
        })
        ->where('employee_id', $id)
        ->get();
       return EmployeePaymentResource::collection($employeePayments);
    }

    public function yearlyEmployeePaymentSumary()
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Get the sum of specified attributes grouped by payment_id
        $yearlyEmployeePaymentSummary = EmployeePayment::with(['payment' ])->select(
            'payment_id',
            DB::raw('SUM(income_tax) as total_income_tax'),
            DB::raw('SUM(total_overtime) as total_overtime'),
            DB::raw('SUM(total_normal_pay_hours) as total_normal_pay_hours'),
            DB::raw('SUM(overtime_pay) as total_overtime_pay'),
            DB::raw('SUM(net_pay) as total_net_pay'),
            DB::raw('SUM(gross_pay) as total_gross_pay'),
            DB::raw('SUM(house_allowance_pay) as total_house_allowance_pay'),
            DB::raw('SUM(longevity_allowance_pay) as total_longevity_allowance_pay'),
            DB::raw('SUM(retirement_deduction) as total_retirement_deduction'),
            DB::raw('SUM(leave_pay) as total_leave_pay'),
            DB::raw('SUM(retirement_pay) as total_retirement_pay'),
        )
         ->whereHas('payment', function ($query) use ($currentYear) {
            $query->whereYear('payment_date', $currentYear);
        })
        ->groupBy('payment_id')
        ->get();

        return YearlyEmployeePaymentSummaryResource::collection($yearlyEmployeePaymentSummary);
    }

    public function mostRecentPaymentSummary()
    {
        // Get the most recent payment ID
        $mostRecentPaymentId = EmployeePayment::max('payment_id');

        // If there are no payments, return an empty collection
        if (!$mostRecentPaymentId) {
            return response()->json([], 200);
        }

        // Get the sum of specified attributes for the most recent payment ID
        $mostRecentEmployeePaymentSummary = EmployeePayment::with(['payment'])->select(
            'payment_id',
            DB::raw('SUM(income_tax) as total_income_tax'),
            DB::raw('SUM(total_overtime) as total_overtime'),
            DB::raw('SUM(total_normal_pay_hours) as total_normal_pay_hours'),
            DB::raw('SUM(overtime_pay) as total_overtime_pay'),
            DB::raw('SUM(net_pay) as total_net_pay'),
            DB::raw('SUM(gross_pay) as total_gross_pay'),
            DB::raw('SUM(house_allowance_pay) as total_house_allowance_pay'),
            DB::raw('SUM(longevity_allowance_pay) as total_longevity_allowance_pay'),
            DB::raw('SUM(retirement_deduction) as total_retirement_deduction'),
            DB::raw('SUM(leave_pay) as total_leave_pay'),
            DB::raw('SUM(retirement_pay) as total_retirement_pay'),
            DB::raw('COUNT(DISTINCT employee_id) as total_employees_worked'),
            DB::raw('COUNT(DISTINCT CASE WHEN leave_pay > 0 THEN employee_id END) as total_employees_on_leave'),
            DB::raw('COUNT(DISTINCT CASE WHEN retirement_pay > 0 THEN employee_id END) as total_employees_on_retirement'),
            DB::raw('SUM(CASE WHEN payments.is_effected = false THEN gross_pay ELSE 0 END) as pending_pay')
        )
        ->join('payments', 'employee_payments.payment_id', '=', 'payments.id')
        ->where('employee_payments.payment_id', $mostRecentPaymentId)
        ->groupBy('payment_id')
        ->get();

        return MostRecentEmployeePaymentSummaryResource::collection($mostRecentEmployeePaymentSummary);
    }



}
