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
         DB::table('attendances')->insert([
            [
                'id' => 1,
                'employee_id' => 1,
                'work_date' => '2024-06-01',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'employee_id' => 2,
                'work_date' => '2024-06-01',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'employee_id' => 3,
                'work_date' => '2024-06-01',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'employee_id' => 4,
                'work_date' => '2024-06-01',
                'status' => 'present',
                'normal_pay_hours' => 7,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'employee_id' => 5,
                'work_date' => '2024-06-01',
                'status' => 'present',
                'normal_pay_hours' => 5,
                'overtime_hour' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'employee_id' => 6,
                'work_date' => '2024-06-01',
                'status' => 'absent',
                'normal_pay_hours' => 0,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'employee_id' => 1,
                'work_date' => '2024-06-02',
                'status' => 'absent',
                'normal_pay_hours' => 0,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'employee_id' => 2,
                'work_date' => '2024-06-02',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'employee_id' => 3,
                'work_date' => '2024-06-02',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'employee_id' => 4,
                'work_date' => '2024-06-02',
                'status' => 'present',
                'normal_pay_hours' => 7,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'employee_id' => 5,
                'work_date' => '2024-06-02',
                'status' => 'present',
                'normal_pay_hours' => 5,
                'overtime_hour' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'employee_id' => 6,
                'work_date' => '2024-06-02',
                'status' => 'present',
                'normal_pay_hours' => 0,
                'overtime_hour' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'employee_id' => 1,
                'work_date' => '2024-05-01',
                'status' => 'present',
                'normal_pay_hours' => 8,
                'overtime_hour' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
