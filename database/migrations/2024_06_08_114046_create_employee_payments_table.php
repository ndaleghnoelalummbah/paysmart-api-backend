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
            $table->date('payment_date')->nullable();
            $table->double('income_tax');
            $table->integer('total_overtime');
            $table->integer('total_hours_worked');
            $table->double('overtime_pay');
            $table->double('net_pay');
            $table->double('gross_pay');
            $table->double('house_allowance_pay');
            $table->double('longevity_allowance_pay');
            $table->double('retirement_deduction');
            $table->double('leave_pay')->nullable();
            $table->double('retirement_pay')->nullable();
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
