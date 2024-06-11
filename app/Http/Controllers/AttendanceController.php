<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

     // Retrieve and summarize monthly attendance for all employees for the current year

    public function index(Request $request)
    {
       // $departments = Department::all();
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Retrieve parameters from the request
        $params = $request->only(['matricule', 'position', 'department', 'min_overtime', 'min_absences', 'month']);

        $matricule = $params['matricule'] ?? null;
        $position = $params['position'] ?? null;
        $departmentName = $params['department'] ?? null;
        $minOvertime = $params['min_overtime'] ?? null;
        $minAbsences = $params['min_absences'] ?? null;
        $monthName = $params['month'] ?? null;

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

        $months = [
        'January' => 1,
        'February' => 2,
        'March' => 3,
        'April' => 4,
        'May' => 5,
        'June' => 6,
        'July' => 7,
        'August' => 8,
        'September' => 9,
        'October' => 10,
        'November' => 11,
        'December' => 12,
    ];
        // Convert the month name to a month number
        $monthNumber = $months[ucfirst(strtolower($monthName))] ?? null;

        // Build the query using Query Builder
         $query = Attendance::with('employee')->select(
            'attendances.employee_id',
            DB::raw('MONTH(work_date) as month'),
            DB::raw('SUM(hours_worked) as total_hours_worked'),
            DB::raw('SUM(overtime_hour) as total_overtime_hour'),
            DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as total_absences')
        )
        ->join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->whereYear('work_date', $currentYear)
        ->groupBy(DB::raw('MONTH(work_date)'),'attendances.employee_id');

        // Apply filters based on parameters
        $query->when($matricule, function ($query, $matricule) {
            $query->where('employees.matricule', 'like', '%' . $matricule . '%');
        })
        ->when($position, function ($query, $position) {
            $query->where('employees.position', 'like', '%' . $position . '%');
        })
        ->when($departmentId, function ($query, $departmentId) {
            $query->where('employees.department_id', 'like', '%' .$departmentId . '%');
        })
        ->when($minOvertime, function ($query, $minOvertime) {
            $query->having(DB::raw('SUM(overtime_hour)'), '>=', $minOvertime);
        })

        ->when($minAbsences, function ($query, $minAbsences) {
            $query->having(DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END)'), '>=', $minAbsences);
        })
        ->when($monthNumber, function ($query, $monthNumber) {
            $query->where(DB::raw('MONTH(work_date)'), $monthNumber);
        });

        // Execute the query and get the results
        $yearlyAttendanceSummary = $query->paginate(24);
        
        if ($yearlyAttendanceSummary->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No employee was found'], 404);
        }

        // Return the summarized attendance data as a resource collection
        return AttendanceResource::collection($yearlyAttendanceSummary);
    }


        /**
         * get an employee attendance summary for the year, group by the month
         */
        public function showEmployeeYearlyAttendance(Request $request, $id)
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Retrieve and summarize monthly attendance for the specified employee for the current year
        $query = Attendance::with('employee')->select(
            'attendances.employee_id',
            // 'employees.name as employee_name',
            // 'employees.matricule as employee_matricule',
            DB::raw('MONTH(work_date) as month'),
            DB::raw('SUM(hours_worked) as total_hours_worked'),
            DB::raw('SUM(overtime_hour) as total_overtime_hour'),
            DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as total_absences')
        )
        ->join('employees', 'attendances.employee_id', '=', 'employees.id')
        ->where('employee_id', $id)
        ->whereYear('work_date', $currentYear)
        ->groupBy(DB::raw('MONTH(work_date)'),'attendances.employee_id');
        
        $employeeAttendanceSummary = $query->get();

        // Return the summarized attendance data as a resource collection
        return AttendanceResource::collection($employeeAttendanceSummary);
    }


}
