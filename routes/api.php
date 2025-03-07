<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\Api\OrderController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);  
    Route::get('/cart/index', [CartController::class, 'index']);      
    Route::post('/cart/add', [CartItemController::class, 'add']);
    Route::put('/cart/update/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart/remove/{id}', [CartItemController::class, 'removeCartItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/callback', [OrderController::class, 'midtransCallback']);
});
