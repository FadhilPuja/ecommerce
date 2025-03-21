<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\CartController;
    use App\Http\Controllers\Api\ProductController;
    use App\Http\Controllers\Api\UserController;
    use App\Http\Controllers\Api\CartItemController;
    use App\Http\Controllers\Api\CategoryController;
    use App\Http\Controllers\Api\MidtransController;
    use App\Http\Controllers\Api\NotificationController;
    use App\Http\Controllers\Api\OrderController;

    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/midtrans/callback', [OrderController::class, 'callback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products', [ProductController::class, 'filter']);
        Route::get('/category', [CategoryController::class, 'index']);    
        Route::get('/cart/index', [CartController::class, 'index']);
        Route::post('/cart/add', [CartItemController::class, 'add']);
        Route::delete('/cart/clear', [CartController::class, 'clearCart']);
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::get('/notification', [NotificationController::class, 'index']);
        Route::post('/notification/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::get('/category/{category_id}', [CategoryController::class, 'detail']);
        Route::put('/cart/update/{id}', [CartItemController::class, 'update']);
        Route::delete('/cart/remove/{id}', [CartItemController::class, 'removeCartItem']);
    });
