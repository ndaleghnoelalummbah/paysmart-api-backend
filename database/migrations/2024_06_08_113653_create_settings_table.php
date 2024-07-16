<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('full_day_payable_hours');
            $table->integer('half_day_payable_hours');
            $table->string('half_day');
            $table->integer('retirement_age')->comment('Age at which employees are eligible for retirement');
            $table->decimal('income_tax_rate', 5, 3)->comment('Percentage of income tax');
            $table->decimal('longevity_bonus', 5, 3)->comment('Bonus given for longevity in service');
            $table->integer('minimum_seniority_age')->comment('Minimum age for seniority benefits');
            // New columns based on social insurance contribution
            $table->decimal('housing_loan_fund_employee_rate', 5, 3)->comment('Employee rate for Housing Loan Fund');
            $table->decimal('housing_loan_fund_employer_rate', 5, 3)->comment('Employer rate for Housing Loan Fund');
            $table->decimal('nef_employer_rate', 5, 3)->comment('Employer rate for National Employment Fund (NEF)');
            $table->decimal('family_allowances_employer_rate', 5, 3)->comment('Employer rate for Family Allowances');
            $table->decimal('pension_disability_employee_rate', 5, 3)->comment('Employee rate for Pension and Disability');
            $table->decimal('pension_disability_employer_rate', 5, 3)->comment('Employer rate for Pension and Disability');
            $table->decimal('work_related_accident_employer_rate', 5, 3)->comment('Employer rate for Work Related Accident and Sickness');
            // New columns based on tax deduction
            $table->double('minimum_salary_for_tax')->comment('Minimum salary for tax deduction');
            $table->double('fixed_deduction_amount')->comment('Fixed deduction amount for tax calculation');
            $table->decimal('tax_rate_1', 5, 2)->comment('Tax rate for income between 62,000 and 2,000,000 CFA');
            $table->decimal('tax_rate_2', 5, 2)->comment('Tax rate for income between 2,000,001 and 3,000,000 CFA');
            $table->decimal('tax_rate_3', 5, 2)->comment('Tax rate for income between 3,000,001 and 5,000,000 CFA');
            $table->decimal('tax_rate_4', 5, 2)->comment('Tax rate for income above 5,000,000 CFA');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
