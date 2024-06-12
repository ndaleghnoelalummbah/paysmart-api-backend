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
       $data = [
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
            'employment_date' => $this->employment_date->format('Y-m-d'),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            
            
        ];
         // Check if the fields are not null before adding them to the response
        if ($this->total_overtime_hour !== null) {
            $data['total_overtime_hour'] = $this->total_overtime_hour;
        }

        if ($this->total_sick_days !== null) {
            $data['total_sick_days'] = $this->total_sick_days;
        }

        if ($this->total_absences !== null) {
            $data['total_absences'] = $this->total_absences;
        }

        return $data;
    }
}
