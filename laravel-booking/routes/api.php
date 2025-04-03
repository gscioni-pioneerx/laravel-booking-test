<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/customer', CustomerController::class);
Route::resource('/booking', BookingController::class);
