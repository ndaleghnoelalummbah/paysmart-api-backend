<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{
    protected function filter(array $filter = NULL)
    {
        $id = $filter['id'] ?? null;
        $name = $filter['name'] ?? null;
        $email = $filter['email'] ?? null;
        $position = $filter['position'] ?? null;
        $work_status = $filter['work_status'] ?? null;

        $employees = Employee::when($id, function ($query, $id) {       //$query is the query builder instance for the Employe Model
                return $query->where('id', $id);
            })
            ->when($name, function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($email, function ($query, $email) {
                return $query->where('email', 'like', '%' . $email . '%');
            })
            ->when($position, function ($query, $position) {
                return $query->where('position', 'like', '%' . $position . '%');
            })
            ->when($work_status, function ($query, $work_status) {
                return $query->where('work_status', $work_status);
            });
            
        return $employees;
    }


    // Index method to return filtered and paginated results
    public function index(Request $request)
    {
        // Get filter criteria from the request
        $filter = $request->only(['id', 'name', 'email', 'position', 'work_status']);

        // Apply filter and get paginated results
        $employees = $this->filter($filter)->paginate(24); // Adjust the pagination limit as needed

        return EmployeeResource::collection($employees);
    }

     public function show(Request $request, $id)
    {
        return new EmployeeResource(Employee::with('department')->findOrFail($id));

    }

    

   
}
