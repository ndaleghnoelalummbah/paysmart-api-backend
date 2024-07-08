<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

         DB::table('employees')->insert([
            [
                'id' => 1,
                'name' => 'John Doe',
                'matricule' => 'EMP001',
                'email' => 'ndaleghnoelalum@gmail.com',
                'phone' => '123-456-7890',
                'position' => 'Manager',
                'employment_date' => '2015-06-01',
                'work_status' => 'active',
                'hourly_income' => 30,
                'housing_allowance' => 500,
                'department_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'matricule' => 'EMP002',
                'email' => 'noelandalegh@gmail.com',
                'phone' => '123-456-7891',
                'position' => 'Engineer',
                'employment_date' => '2016-07-15',
                'work_status' => 'active',
                'hourly_income' => 40,
                'housing_allowance' => 600,
                'department_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Jim Brown',
                'matricule' => 'EMP003',
                'email' => 'noelalum05@gmail.com',
                'phone' => '123-456-7892',
                'position' => 'Marketer',
                'employment_date' => '2018-01-10',
                'work_status' => 'active',
                'hourly_income' => 35,
                'housing_allowance' => 550,
                'department_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Susan White',
                'matricule' => 'EMP004',
                'email' => 'couragendalegh@gmail.com',
                'phone' => '123-456-7893',
                'position' => 'Sales Associate',
                'employment_date' => '2017-03-20',
                'work_status' => 'active',
                'hourly_income' => 32,
                'housing_allowance' => 520,
                'department_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Paul Green',
                'matricule' => 'EMP005',
                'email' => 'amosongodina@gmail.com',
                'phone' => '123-456-7894',
                'position' => 'Financial Analyst',
                'employment_date' => '2019-11-25',
                'work_status' => 'active',
                'hourly_income' => 38,
                'housing_allowance' => 580,
                'department_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
