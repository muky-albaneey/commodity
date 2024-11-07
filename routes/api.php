<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/customers', [UserController::class, 'customers']);
        Route::post('/customers', [UserController::class, 'createCustomer']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'createUser']);
        Route::put('/{user}', [UserController::class, 'updateUser']);
        Route::delete('/{user}', [UserController::class, 'deleteUser']);
        
        // User status management routes
        Route::post('/{user}/suspend', [UserController::class, 'suspendUser']);
        Route::post('/{user}/unsuspend', [UserController::class, 'unsuspendUser']);
        Route::post('/{user}/make-admin', [UserController::class, 'makeAdmin']);
        Route::post('/{user}/remove-admin', [UserController::class, 'removeAdmin']);
    });
});