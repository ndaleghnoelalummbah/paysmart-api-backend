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
        Schema::create('employee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('payment_id');
            $table->integer('total_overtime');
            $table->integer('total_normal_pay_hours');
            $table->double('overtime_pay');
            $table->double('house_allowance_pay');
            $table->double('longevity_allowance_pay');
            $table->double('leave_pay');
            $table->double('gross_pay');
            $table->double('income_tax');
            $table->double('employee_cnps_contribution');
            $table->double('employer_cnps_contribution');
            $table->double('net_pay');
            $table->timestamps();
            
            $table->foreign('employee_id')->references('id')
                ->on('employees') 
                ->onUpdate('cascade')
                ->onDelete('cascade');
               
            $table->foreign('admin_id')->references('id')
                ->on('admins')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('payment_id')->references('id')
                ->on('payments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payments');
    }
};
