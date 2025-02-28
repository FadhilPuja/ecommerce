<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

    Route::prefix('dashboard')->name('dashboard.')->group(function(){
        Route::get('/', [UserController::class, 'dashboard'])->name('index');
    });
});
