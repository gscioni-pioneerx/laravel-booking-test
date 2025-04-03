<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('customers', CustomerController::class);
//     Route::apiResource('bookings', BookingController::class);
//     Route::get('bookings/export/csv', [BookingController::class, 'exportCsv']);
//     Route::get('customers/{customerId}/bookings', [BookingController::class, 'getBookingsByCustomer']);
// });

// For testing without authentication
Route::prefix('v1')->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::get('customers/{customerId}/bookings', [BookingController::class, 'getBookingsByCustomer']);

    Route::apiResource('bookings', BookingController::class);
    Route::get('bookings/export/csv', [BookingController::class, 'exportCsv']);
    Route::get('bookings/active', [BookingController::class, 'getActiveBookings']);
    Route::get('bookings/future', [BookingController::class, 'getFutureBookings']);
    Route::get('bookings/status/{status}', [BookingController::class, 'getBookingsByStatus']);
});