<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('/customer', CustomerController::class)->middleware('auth:sanctum');
Route::apiResource('/booking', BookingController::class)->middleware('auth:sanctum');

Route::get('/export/customers', [CustomerController::class, 'export'])->middleware('auth:sanctum');
Route::get('/export/bookings', [BookingController::class, 'export'])->middleware('auth:sanctum');
