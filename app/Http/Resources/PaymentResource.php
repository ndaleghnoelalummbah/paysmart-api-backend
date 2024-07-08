<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id' => $this->id,
        'admin_id' => $this->admin_id,
        'total_income_tax' => $this->total_income_tax,
        'total_cnps_contribution' => $this->total_cnps_contribution,
        'total_pay' => $this->total_pay,
        'total_pay_with_cnps' => $this->total_pay_with_cnps,
        'is_effected' => $this->is_effected,
        'payment_date' => $this->payment_date,
        'payslip_issue_date' => $this->payslip_issue_date,
    ];
    }
}


