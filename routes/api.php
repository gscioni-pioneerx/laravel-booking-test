<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;

Route::post('/login', AuthController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/attach-customer', [BookingController::class, 'attachCustomer']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
});
