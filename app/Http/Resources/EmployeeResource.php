<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'name' => $this->name,
            'matricule' => $this->matricule,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'employment_date' => $this->employment_date,
            'work_status' => $this->work_status,
            'hourly_income' => $this->hourly_income,
            'hourly_overtime_pay' => $this->hourly_overtime_pay,
            'housing_allowance' => $this->housing_allowance,
            'employment_date' => $this->employment_date,
             'department' => new DepartmentResource($this->whenLoaded('department')),
            // 'relationship' => [
            //     'department' => DepartmentResource::collection($this->whenLoaded('department'))
            // ]
        ];
    }
}
