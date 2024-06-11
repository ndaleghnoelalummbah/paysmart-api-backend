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
            'retirement_age' => 65,
            'income_tax_rate' => 0.030,
            'retirement_contribution_rate' => 0.040,
            'longevity_bonus' => 0.002,
            'minimum_seniority_age' => 5,
        ]);
    }
}
