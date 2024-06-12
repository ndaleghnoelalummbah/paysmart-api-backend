<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Leave;
use Carbon\Carbon;
use illuminate\support\Facades\DB;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('leaves')->insert([
            [
                'id' => 1,
                'employee_id' => 1,
                'start_date' => '2024-07-11',
                'resumption_date' => '2024-11-15',
                'is_paid' => 0,
                'leave_pay' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'employee_id' => 2,
                'start_date' => '2024-07-01',
                'resumption_date' => '2024-11-20',
                'is_paid' => 0,
                'leave_pay' => 200,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::parse('2024-06-10 06:01:36'),
            ],
            [
                'id' => 3,
                'employee_id' => 3,
                'start_date' => '2024-09-01',
                'resumption_date' => '2025-01-15',
                'is_paid' => 0,
                'leave_pay' => 300,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'employee_id' => 4,
                'start_date' => '2024-06-01',
                'resumption_date' => '2024-12-10',
                'is_paid' => 0,
                'leave_pay' => 400,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::parse('2024-06-10 06:01:36'),
            ],
            [
                'id' => 5,
                'employee_id' => 5,
                'start_date' => '2024-06-11',
                'resumption_date' => '2024-12-12',
                'is_paid' => 0,
                'leave_pay' => 500,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
