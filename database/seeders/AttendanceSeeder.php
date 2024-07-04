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
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Get the number of days in the current month
       // $daysInMonth = Carbon::now()->daysInMonth;
        $curr_day = Carbon::now()->day;

        // Define statuses
        $statuses = ['present', 'absent', 'sick', 'holiday'];

        // Loop through each day of the month
        for ($day = 1; $day <= $curr_day; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day)->toDateString();

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

                // Set overtime hours
                $overtimeHours = 0;
                if ($status === 'present') {
                    $overtimeHours = rand(0, 3);
                }

                // Create attendance record
                DB::table('attendances')->insert([
                    'employee_id' => $employeeId,
                    'work_date' => $date,
                    'status' => $status,
                    'normal_pay_hours' => $normalPayHours,
                    'overtime_hour' => $overtimeHours,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}