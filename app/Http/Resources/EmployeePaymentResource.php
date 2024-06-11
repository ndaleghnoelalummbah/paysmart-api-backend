<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeePaymentResource extends JsonResource
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
            'total_hours_worked' => $this->total_hours_worked,
            'total_overtime_pay' => $this->total_overtime_pay,
            'net_pay' => $this->net_pay,
            'gross_pay' => $this->gross_pay,
            'house_allowance_pay' => $this->house_allowance_pay,
            'longevity_allowance_pay' => $this->longevity_allowance_pay,
            'retirement_deduction' => $this->retirement_deduction,
            'leave_pay' => $this->leave_pay,
            'retirement_pay' => $this->retirement_pay,
            'admin' => new AdminResource($this->whenLoaded('admin')),
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            // 'relationship' => [
            //     'payment' => PaymentResource::collection($this->whenLoaded('payment')),
            //     'admin' => AdminResource::collection($this->whenLoaded('admin'))
            // ]
        ];
    }
}
