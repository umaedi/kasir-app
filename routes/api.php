<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/check-auth', [AuthController::class, 'checkAuth']);
    
    // Product routes
    Route::apiResource('products', ProductController::class);
    Route::get('/products/categories', [ProductController::class, 'categories']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStock']);
    Route::post('/products/{product}/stock', [ProductController::class, 'updateStock']);
    
    // Transaction routes
    Route::apiResource('transactions', TransactionController::class);
    Route::post('/transactions/batch', [TransactionController::class, 'storeBatch']);
    Route::get('/transactions/report/daily', [TransactionController::class, 'dailyReport']);
    Route::get('/transactions/statistics', [TransactionController::class, 'statistics']);
// });

// Admin only routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Routes khusus admin
});