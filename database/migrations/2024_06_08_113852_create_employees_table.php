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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('matricule')->unique();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('position');
            $table->date('employment_date');
            $table->string('work_status');
            $table->double('hourly_income');
            $table->double('housing_allowance');
            $table->unsignedBigInteger('department_id');
            $table->string('stripe_customer_id')->nullable();
            $table->timestamps();
            
            $table->foreign('department_id')->references('id')
            ->on('departments')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
