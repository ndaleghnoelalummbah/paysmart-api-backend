<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'month' => $this->month,
            'total_normal_pay_hours' => $this->total_normal_pay_hours,
            'total_overtime_hour' => $this->total_overtime_hour,
            'total_absences' => $this->total_absences,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            // 'relationship' => [
            //     'employee' => EmployeeResource::collection($this->whenLoaded('employee'))
            // ]
        ];
    }
}
