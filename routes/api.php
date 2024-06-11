<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeePaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;

// Group routes with version and language prefix
Route::prefix('{version}/{lang}')->middleware('identify_parameters')->group(function () {

    // Routes for authenticated users
    Route::middleware('auth:sanctum')->group(function () {

        // Employee routes
        Route::prefix('employees')->group(function () {
            Route::get('/', [AttendanceController::class, 'index']); // List all employees (paginated)
            Route::get('/{id}', [EmployeeController::class, 'show']); // Show details of a specific employee
            Route::get('/attendances/{id}', [AttendanceController::class, 'showEmployeeYearlyAttendance']); // Show attendance summary for an employee for the current year
            Route::get('/payments/{id}', [EmployeePaymentController::class, 'show']); // Show payment details for an employee for the current year
        });

        // // Attendance routes
        // Route::prefix('attendance')->group(function () {
        //     Route::get('/yearly-summary', [AttendanceController::class, 'showYearlyAttendance']); // Show yearly attendance summary for all employees
        // });

        // Payment routes
        Route::prefix('payments')->group(function () {
            Route::get('/yearly-summary', [EmployeePaymentController::class, 'yearlyEmployeePaymentSumary']); // Show yearly payment summary
            Route::get('/most-recent-summary', [EmployeePaymentController::class, 'mostRecentPaymentSummary']); // Show most recent payment summary
        });

        // Initiate and process payments
        Route::post('/initiate-payment', [PaymentController::class, 'initiatePayment']); // Initiate payments
        Route::post('/make-payment/{payment}', [PaymentController::class, 'makePayment']); // Process payment

    });

    // Authentication routes
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', [AuthController::class, 'login']);  //all admins login
        Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });
  

    Route::prefix('admins')->controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->middleware('auth:sanctum');
    Route::post('/create', 'create')->middleware('auth:sanctum');
    Route::delete('/delete/{id}', 'destroy')->middleware('auth:sanctum');
});


});
