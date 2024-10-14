<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\TradeController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // List all users
    Route::get('/{id}', [UserController::class, 'show']); // View a single user
    Route::post('/', [UserController::class, 'store']); // Create a new user
    Route::put('/{id}', [UserController::class, 'update']); // Update an existing user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Delete a user
});

Route::prefix('wallets')->group(function () {
    Route::post('/{userId}/fund', [WalletController::class, 'fund']); // Fund the wallet
    Route::get('/{userId}', [WalletController::class, 'show']); // Show wallet balance
});
// Route::post('wallets/{userId}/fund',[WalletController::class, 'fund']);

Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']); // List all transactions
    Route::get('/{id}', [TransactionController::class, 'show']); // View a single transaction
});

Route::prefix('commodities')->group(function () {
    Route::get('/', [CommodityController::class, 'index']); // List all commodities
    Route::get('/{id}', [CommodityController::class, 'show']); // View a single commodity
    Route::post('/', [CommodityController::class, 'store']); // Create a new commodity
    Route::put('/{id}', [CommodityController::class, 'update']); // Update an existing commodity
    Route::delete('/{id}', [CommodityController::class, 'destroy']); // Delete a commodity
});

Route::prefix('trades')->group(function () {
    Route::post('/{userId}/buy/{commodityId}', [TradeController::class, 'buy']); // Buy a commodity
});
