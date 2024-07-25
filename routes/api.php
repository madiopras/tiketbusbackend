<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\Master\UsersController;
use App\Http\Controllers\Admin\Master\ClassesController; // Tambahkan ini
use App\Http\Controllers\Admin\Master\BusesController; // Tambahkan ini


// Register and Login
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('user', [AuthController::class, 'user']);

    // Users CRUD operations
    Route::prefix('admin')->group(function () {
        Route::apiResource('users', UsersController::class);
        Route::apiResource('classes', ClassesController::class); // Tambahkan ini
        Route::apiResource('busses', BusesController::class); // Tambahkan ini
    });
});
