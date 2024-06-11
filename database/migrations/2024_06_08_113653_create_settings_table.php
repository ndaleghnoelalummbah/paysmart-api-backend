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
            $table->integer('retirement_age')->comment('Age at which employees are eligible for retirement');
            $table->decimal('income_tax_rate', 5, 3)->comment('Percentage of income tax');
            $table->decimal('retirement_contribution_rate', 5, 3)->comment('Percentage of salary deducted for retirement');
            $table->decimal('longevity_bonus', 5, 3)->comment('Bonus given for longevity in service');
            $table->integer('minimum_seniority_age')->comment('Minimum age for seniority benefits');
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
