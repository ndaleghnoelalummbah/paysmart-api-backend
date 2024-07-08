<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends Controller
{
public function index(Request $request)
{
    // Get the current year
    $currentYear = Carbon::now()->year;

    // Retrieve parameters from the request
    $params = $request->only(['matricule', 'position', 'department', 'min_overtime', 'min_absences', 'min_sick_days']);

    $matricule = $params['matricule'] ?? null;
    $position = $params['position'] ?? null;
    $departmentName = $params['department'] ?? null;
    $minOvertime = $params['min_overtime'] ?? null;
    $minAbsences = $params['min_absences'] ?? null;
    $minSickDays = $params['min_sick_days'] ?? null;

    // Get the department ID if the department name is provided
    $departmentId = null;
    if ($departmentName) {
        $department = Department::where('name', $departmentName)->first();
        if ($department) {
            $departmentId = $department->id;
        } else {
            // Return a 404 response if the department is not found
            return response()->json(['status' => false, 'message' => 'Department not found'], 404);
        }
    }

    // Build the query using Query Builder
    $query = Employee::with('department')->leftJoin('attendances', function ($join) use ($currentYear) {
            $join->on('employees.id', '=', 'attendances.employee_id')
                 ->whereYear('attendances.work_date', $currentYear);
        })
        ->select(

            'employees.id',
            'employees.name',
            'employees.matricule',
            'employees.position',
            'employees.email',
            'employees.phone',
            'employees.employment_date',
            'employees.work_status',
            'employees.hourly_income',
            'employees.housing_allowance',
            'employees.department_id',
            DB::raw('COALESCE(SUM(attendances.overtime_hour), 0) as total_overtime_hour'),
            DB::raw('COALESCE(SUM(CASE WHEN attendances.status = "sick" THEN 1 ELSE 0 END), 0) as total_sick_days'),
            DB::raw('COALESCE(SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END), 0) as total_absences'),
        )
        ->groupBy('employees.id', 'employees.name', 'employees.matricule', 'employees.position', 'employees.department_id','employees.email',
            'employees.phone',
            'employees.employment_date',
            'employees.work_status',
            'employees.hourly_income',
            'employees.housing_allowance');

    // Apply filters based on parameters
    $query->when($matricule, function ($query, $matricule) {
        $query->where('employees.matricule', 'like', '%' . $matricule . '%');
    })
    ->when($position, function ($query, $position) {
        $query->where('employees.position', 'like', '%' . $position . '%');
    })
    ->when($departmentId, function ($query, $departmentId) {
        $query->where('employees.department_id', $departmentId);
    })
    ->when($minOvertime, function ($query, $minOvertime) {
        $query->having(DB::raw('COALESCE(SUM(attendances.overtime_hour), 0)'), '>=', $minOvertime);
    })
    ->when($minAbsences, function ($query, $minAbsences) {
        $query->having(DB::raw('COALESCE(SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END), 0)'), '>=', $minAbsences);
    })
    ->when($minSickDays, function ($query, $minSickDays) {
        $query->having(DB::raw('COALESCE(SUM(CASE WHEN attendances.status = "sick" THEN 1 ELSE 0 END), 0)'), '>=', $minSickDays);
    });

    // Execute the query and get the results
    $yearlyAttendanceSummary = $query->paginate(3);
    // $yearlyAttendanceSummary = $query->get();

    if ($yearlyAttendanceSummary->isEmpty()) {
        return response()->json(['status' => true, 'message' => 'No employee was found', 'data' => []], 200);
    }

    // Return the summarized attendance data as a resource collection
    return EmployeeResource::collection($yearlyAttendanceSummary);
}

     public function show(Request $request, $id)
    {
        return new EmployeeResource(Employee::with('department')->findOrFail($id));

    }
   
}
