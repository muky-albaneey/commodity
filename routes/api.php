<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\BankController;
// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/customers', [UserController::class, 'customers']);
        Route::get('/customers/{customerId}', [UserController::class, 'getCustomer']);
        Route::post('/customers', [UserController::class, 'createCustomer']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'createUser']);
        Route::put('/{user}', [UserController::class, 'updateUser']);
        Route::delete('/customers/delete', [UserController::class, 'deleteUsers']);
        
        // User status management routes
        Route::post('/{user}/suspend', [UserController::class, 'suspendUser']);
        Route::post('/{user}/unsuspend', [UserController::class, 'unsuspendUser']);
        Route::post('/{user}/make-admin', [UserController::class, 'makeAdmin']);
        Route::post('/{user}/remove-admin', [UserController::class, 'removeAdmin']);
    });

    Route::get('/markets', [MarketController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'getStats']);
    Route::get('/news', [NewsController::class, 'index']);

    // Asset Management Routes
    Route::get('/portfolio/overview', [AssetManagementController::class, 'getPortfolioOverview']);
    Route::get('/portfolio/transactions', [AssetManagementController::class, 'getTransactionHistory']);
    
    // Wallet Routes
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    
    // Bank Routes
    Route::get('/banks', [BankController::class, 'getBanks']);
    Route::get('/bank-accounts', [BankController::class, 'getUserBankAccounts']);
    Route::post('/bank-accounts', [BankController::class, 'addBankAccount']);
});