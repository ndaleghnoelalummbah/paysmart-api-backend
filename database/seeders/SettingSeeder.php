<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Setting::create([
            'full_day_payable_hours' => 8,
            'half_day_payable_hours' => 5,
            'retirement_age' => 60,
            'income_tax_rate' => 0.030,
            'longevity_bonus' => 0.002,
            'minimum_seniority_age' => 5,
            'housing_loan_fund_employee_rate' => 0.01, // 1% converted to ratio
            'housing_loan_fund_employer_rate' => 0.015, // 1.5% converted to ratio
            'nef_employer_rate' => 0.01, // 1% converted to ratio
            'family_allowances_employer_rate' => 0.07, // 7% converted to ratio
            'pension_disability_employee_rate' => 0.042, // 4.2% converted to ratio
            'pension_disability_employer_rate' => 0.042, // 4.2% converted to ratio
            'work_related_accident_employer_rate' => 0.05, // 5% converted to ratio
            'minimum_salary_for_tax' => 62000,
            'fixed_deduction_amount' => 500000,
            'tax_rate_1' => 0.10, // 10% converted to ratio
            'tax_rate_2' => 0.15, // 15% converted to ratio
            'tax_rate_3' => 0.25, // 25% converted to ratio
            'tax_rate_4' => 0.35, // 35% converted to ratio
        ]);
    }
}
