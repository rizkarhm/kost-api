<?php

use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\KostController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('user/photo', [UserController::class, 'updatePhoto']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::post('checkout', [BookingController::class, 'checkout']);

    Route::get('booking', [BookingController::class, 'all']);
    Route::post('booking/{id}', [BookingController::class, 'update']);
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::get('kost', [KostController::class, 'all']);

Route::post('midtrans/callback', [MidtransController::class, 'callback']);
