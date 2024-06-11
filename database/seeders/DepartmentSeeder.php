<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create(['name' => 'HR']);
        Department::create(['name' => 'Engineering']);
        Department::create(['name' => 'Marketing']);
        Department::create(['name' => 'Sales']);
        Department::create(['name' => 'Finance']);
        Department::create(['name' => 'Customer Support']);
    }
}
