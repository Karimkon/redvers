<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\SwapController;
use App\Http\Controllers\Api\DashboardController;

// OTP Routes (can live outside versioning if needed for public access)
Route::post('/send-otp', [OTPController::class, 'sendOtp']);
Route::post('/verify-otp', [OTPController::class, 'verifyOtp']);

// API v1 Group
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [ProfileController::class, 'me']);

        Route::get('/stations', [StationController::class, 'index']);
        Route::post('/swaps', [SwapController::class, 'store']);
        Route::get('/swaps/history', [SwapController::class, 'userSwaps']);

        // Admin-specific routes (optional permission middleware)
        Route::middleware('can:is-admin')->group(function () {
            Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
        });
    });
});
