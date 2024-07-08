<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MostRecentEmployeePaymentSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'total_income_tax' => $this->total_income_tax,
            'total_overtime' => $this->total_overtime,
            'total_normal_pay_hours' => $this->total_normal_pay_hours,
            'total_overtime_pay' => $this->total_overtime_pay,
            'total_net_pay' => $this->total_net_pay,
            'total_gross_pay' => $this->total_gross_pay,
            'total_house_allowance_pay' => $this->total_house_allowance_pay,
            'total_longevity_allowance_pay' => $this->total_longevity_allowance_pay,
            'total_cnps_contribution' => $this->total_cnps_contribution,
            'total_leave_pay' => $this->total_leave_pay,
            //'total_retirement_pay' => $this->total_retirement_pay,
            'total_employees_worked' => $this->total_employees_worked,
            'total_employees_on_leave' => $this->total_employees_on_leave,
            'total_employees_on_retirement' => $this->total_employees_on_retirement,
            'pending_pay' => $this->pending_pay,
            'payment' => new PaymentResource($this->whenLoaded('payment')),

        ];
    }
}
