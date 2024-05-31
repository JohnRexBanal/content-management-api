<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// User routes
Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/view/{id}', [AuthController::class, 'show']);
    Route::put('/update/{id}', [AuthController::class, 'update']);
    Route::delete('/delete/{id}', [AuthController::class, 'destroy']);
    Route::get('/count', [AuthController::class, 'count']);
});

// Product routes
Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/create', [ProductController::class, 'store']);
    Route::get('/edit/{id}', [ProductController::class, 'edit']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    Route::get('/view/{id}', [ProductController::class, 'show']);
    Route::get(('/count'), [ProductController::class, 'count']);
});
