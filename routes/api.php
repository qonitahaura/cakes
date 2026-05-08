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
    ReportController,
    CategoryController as AdminCategory,
    CustomizationController as AdminCustomization,
    PaymentController as AdminPayment,
    ReviewController as AdminReview,
};

use App\Http\Controllers\Api\Baker\OrderController as BakerOrder;
use App\Http\Controllers\Api\CustomerService\OrderController as CsOrder;
use App\Http\Controllers\Api\CustomerService\PaymentController as CsPayment;

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

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::middleware('role:customer')->group(function () {

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'add']);

        Route::post('/checkout', [CheckoutController::class, 'checkout']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });

    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::get('/reports/summary', [ReportController::class, 'summary']);
        Route::get('/reports/revenue', [ReportController::class, 'revenueByDay']);
        Route::get('/reports/export/{ext}', [ReportController::class, 'export']);
        Route::get('/reports', [ReportController::class, 'index']);

        Route::get('/users', [AdminUser::class, 'index']);
        Route::post('/users', [AdminUser::class, 'store']);
        Route::get('/users/{id}', [AdminUser::class, 'show']);
        Route::put('/users/{id}', [AdminUser::class, 'update']);
        Route::delete('/users/{id}', [AdminUser::class, 'destroy']);
        Route::post('/users/{id}/role', [AdminUser::class, 'assignRole']);

        Route::post('/products', [AdminProduct::class, 'store']);
        Route::put('/products/{id}', [AdminProduct::class, 'update']);
        Route::delete('/products/{id}', [AdminProduct::class, 'destroy']);
        Route::post('/products/{id}/customizations', [AdminProduct::class, 'attachCustomizations']);

        Route::get('/orders', [AdminOrder::class, 'index']);
        Route::get('/orders/{id}', [AdminOrder::class, 'show']);
        Route::put('/orders/{id}/status', [AdminOrder::class, 'updateStatus']);

        Route::apiResource('categories', AdminCategory::class);
        Route::apiResource('customizations', AdminCustomization::class);
        Route::delete('customizations/{customization}/options/{option}', [AdminCustomization::class, 'destroyOption']);

        Route::get('/payments', [AdminPayment::class, 'index']);
        Route::get('/payments/{id}', [AdminPayment::class, 'show']);

        Route::get('/reviews', [AdminReview::class, 'index']);
        Route::delete('/reviews/{id}', [AdminReview::class, 'destroy']);
    });

    Route::prefix('baker')->middleware('role:baker')->group(function () {
        Route::get('/orders/schedule', [BakerOrder::class, 'schedule']);
        Route::get('/orders', [BakerOrder::class, 'index']);
        Route::get('/orders/{id}', [BakerOrder::class, 'show']);
        Route::put('/orders/{id}/production-status', [BakerOrder::class, 'updateProductionStatus']);
    });

    Route::prefix('cs')->middleware('role:customer_service')->group(function () {
        Route::get('/orders/incoming', [CsOrder::class, 'incoming']);
        Route::get('/orders/pickup-schedule', [CsOrder::class, 'pickupSchedule']);
        Route::get('/orders/history', [CsOrder::class, 'history']);
        Route::get('/orders/{id}', [CsOrder::class, 'show']);
        Route::post('/orders/{id}/validate', [CsOrder::class, 'validateOrder']);

        Route::get('/payments', [CsPayment::class, 'index']);
        Route::post('/payments/{id}/confirm-dp', [CsPayment::class, 'confirmDp']);
        Route::post('/payments/{id}/confirm-full', [CsPayment::class, 'confirmFull']);
    });
});
