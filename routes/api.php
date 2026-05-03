<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    AuthController,
    ProductController,
    CartController,
    CheckoutController,
    OrderController,
    ProfileController,
    CustomizationController
};

use App\Http\Controllers\Api\Admin\{
    UserController as AdminUser,
    ProductController as AdminProduct,
    OrderController as AdminOrder,
    ReportController
};

// PUBLIC
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/customizations', [CustomizationController::class, 'index']);

// PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // CUSTOMER
    Route::middleware('role:customer')->group(function () {

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'add']);

        Route::post('/checkout', [CheckoutController::class, 'checkout']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });

    // ADMIN
    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/users', [AdminUser::class, 'index']);
        Route::put('/users/{id}', [AdminUser::class, 'update']);
        Route::delete('/users/{id}', [AdminUser::class, 'destroy']);

        Route::post('/products', [AdminProduct::class, 'store']);
        Route::put('/products/{id}', [AdminProduct::class, 'update']);
        Route::delete('/products/{id}', [AdminProduct::class, 'destroy']);

        Route::get('/orders', [AdminOrder::class, 'index']);
        Route::put('/orders/{id}/status', [AdminOrder::class, 'updateStatus']);

        Route::get('/reports', [ReportController::class, 'index']);
    });
});
