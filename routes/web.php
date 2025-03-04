<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->name('auth.')->group(function (){
    Route::middleware('guest')->group(function() {
        Route::get('/login', [UserController::class, 'login'])->name('login');
        Route::post('/login', [UserController::class, 'authenticate'])->name('authenticate');
        Route::get('/register', [UserController::class, 'register'])->name('register');
        Route::post('/register', [UserController::class, 'store'])->name('store');
    });

    Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
});

Route::middleware('auth')->group(function() {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard.index');

    Route::prefix('customer/home')->name('home.')->group(function(){
        Route::get('/index', [HomeController::class, 'index'])->name('index');
        Route::get('/show/{id}', [HomeController::class, 'show'])->name('show');
    });

    Route::prefix('customer/cart')->name('cart.')->group(function(){
        Route::get('/index', [CartController::class, 'index'])->name('index');
    });

    Route::middleware(['admin'])->group(function() {
        Route::prefix('admin/category')->name('category.')->group(function(){
            Route::get('/index', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/create', [CategoryController::class, 'store'])->name('store');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admin/products')->name('products.')->group(function(){
            Route::get('/index', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/create', [ProductController::class, 'store'])->name('store');
            Route::get('/{id}', [ProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        });
    });
});
