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
        'is_effected' => $this->is_effected,
        'payment_date' => $this->payment_date->format('Y-m-d'),
        'payslip_issue_date' => $this->payslip_issue_date,
    ];
    }
}
