<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
| Here is where you can register web routes for your application.
| Routes are loaded by the RouteServiceProvider and assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest')->group(function() {
        Route::get('/login', [UserController::class, 'login'])->name('login');
        Route::post('/login', [UserController::class, 'authenticate'])->name('authenticate');
        Route::get('/register', [UserController::class, 'register'])->name('register');
        Route::post('/register', [UserController::class, 'store'])->name('store');
    });

    Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
});

// Authenticated routes
Route::middleware('auth')->group(function() {

    // Admin Dashboard route
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Customer routes
    Route::prefix('customer/home')->name('home.')->group(function(){
        Route::get('/index', [HomeController::class, 'index'])->name('index');
        Route::get('/show/{id}', [HomeController::class, 'show'])->name('show');
    });

    Route::prefix('customer/cart')->name('cart.')->group(function(){
        Route::get('/index', [CartController::class, 'index'])->name('index');
    });

    // Admin routes with admin middleware
    Route::middleware(['admin'])->group(function() {

        // Category routes
        Route::prefix('admin/category')->name('category.')->group(function(){
            Route::get('/index', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/create', [CategoryController::class, 'store'])->name('store');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // Product routes
        Route::prefix('admin/products')->name('products.')->group(function(){
            Route::get('/index', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/create', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}', [ProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        });

        // Order routes
        Route::prefix('admin/order')->name('order.')->group(function(){
            Route::get('/index', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        });

        // Customer management routes
        Route::prefix('admin/customers')->name('customers.')->group(function(){
            Route::get('/index', [CustomerController::class, 'index'])->name('index');
        });

        // Settings routes
        Route::prefix('admin/setting')->name('setting.')->group(function(){
            Route::get('/index', [SettingController::class, 'index'])->name('index');
            Route::put('/index', [SettingController::class, 'update'])->name('update');
        });
    });
});
