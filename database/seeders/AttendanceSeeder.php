<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now()->startOfYear()->month(6)->startOfMonth();
        $endDate = Carbon::now();

        // Define statuses
        $statuses = ['present', 'absent', 'sick', 'holiday'];

        // Loop through each day from June to the current date
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Loop through each employee
            for ($employeeId = 1; $employeeId <= 5; $employeeId++) {
                // Choose a random status
                $status = $statuses[array_rand($statuses)];

                // Set normal pay hours based on the status
                $normalPayHours = 0;
                if ($status === 'present') {
                    $normalPayHours = rand(1, 8);
                } elseif ($status === 'sick') {
                    $normalPayHours = [5, 8][array_rand([5, 8])];
                } elseif ($status === 'holiday') {
                    $normalPayHours = [5, 8][array_rand([5, 8])];
                } elseif ($status === 'absent') {
                    $normalPayHours = 0;
                }

                // Set overtime hours and overtime rate
                $overtimeHours = 0;
                $overtimeRate = 0.00;

                if ($normalPayHours > 5 && $status === 'present') {
                    $overtimeHours = rand(0, 3);
                    if ($overtimeHours > 0) {
                        $overtimeRate = [1.20, 1.30, 1.40, 1.50][array_rand([1.20, 1.30, 1.40, 1.50])];
                    }
                }

                // Create attendance record
                DB::table('attendances')->insert([
                    'employee_id' => $employeeId,
                    'work_date' => $date->toDateString(),
                    'status' => $status,
                    'normal_pay_hours' => $normalPayHours,
                    'overtime_hour' => $overtimeHours,
                    'overtime_rate' => $overtimeRate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
