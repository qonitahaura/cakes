<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

Route::view('/login', 'auth.login', [
    'title' => 'Login',
    'page' => 'login',
])->name('login');

Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard', [
        'title' => 'Dashboard',
        'role' => 'admin',
        'active' => 'dashboard',
        'page' => 'admin-dashboard',
    ]);
    Route::view('/users', 'admin.users', [
        'title' => 'User Management',
        'role' => 'admin',
        'active' => 'users',
        'page' => 'admin-users',
    ]);
    Route::view('/categories', 'admin.categories', [
        'title' => 'Categories',
        'role' => 'admin',
        'active' => 'categories',
        'page' => 'admin-categories',
    ]);
    Route::view('/products', 'admin.products', [
        'title' => 'Products',
        'role' => 'admin',
        'active' => 'products',
        'page' => 'admin-products',
    ]);
    Route::view('/customizations', 'admin.customizations', [
        'title' => 'Cake Customizations',
        'role' => 'admin',
        'active' => 'customizations',
        'page' => 'admin-customizations',
    ]);
    Route::view('/orders', 'admin.orders', [
        'title' => 'Orders',
        'role' => 'admin',
        'active' => 'orders',
        'page' => 'admin-orders',
    ]);
    Route::get('/orders/{id}', function (string $id) {
        return view('admin.orders-show', [
            'title' => 'Order #'.$id,
            'role' => 'admin',
            'active' => 'orders',
            'page' => 'admin-orders-show',
            'orderId' => $id,
        ]);
    })->whereNumber('id');
    Route::view('/payments', 'admin.payments', [
        'title' => 'Payments',
        'role' => 'admin',
        'active' => 'payments',
        'page' => 'admin-payments',
    ]);
    Route::view('/reports', 'admin.reports', [
        'title' => 'Reports',
        'role' => 'admin',
        'active' => 'reports',
        'page' => 'admin-reports',
    ]);
    Route::view('/reviews', 'admin.reviews', [
        'title' => 'Reviews',
        'role' => 'admin',
        'active' => 'reviews',
        'page' => 'admin-reviews',
    ]);
});

Route::prefix('baker')->group(function () {
    Route::view('/dashboard', 'baker.dashboard', [
        'title' => 'Baker Dashboard',
        'role' => 'baker',
        'active' => 'dashboard',
        'page' => 'baker-dashboard',
    ]);
    Route::view('/orders', 'baker.orders', [
        'title' => 'Production Orders',
        'role' => 'baker',
        'active' => 'orders',
        'page' => 'baker-orders',
    ]);
    Route::view('/schedule', 'baker.schedule', [
        'title' => 'Production Schedule',
        'role' => 'baker',
        'active' => 'schedule',
        'page' => 'baker-schedule',
    ]);
    Route::view('/completed', 'baker.completed', [
        'title' => 'Completed Orders',
        'role' => 'baker',
        'active' => 'completed',
        'page' => 'baker-completed',
    ]);
});

Route::prefix('cs')->group(function () {
    Route::view('/dashboard', 'cs.dashboard', [
        'title' => 'Customer Service',
        'role' => 'customer_service',
        'active' => 'dashboard',
        'page' => 'cs-dashboard',
    ]);
    Route::view('/incoming', 'cs.incoming', [
        'title' => 'Incoming Orders',
        'role' => 'customer_service',
        'active' => 'incoming',
        'page' => 'cs-incoming',
    ]);
    Route::view('/validation', 'cs.validation', [
        'title' => 'Order Validation',
        'role' => 'customer_service',
        'active' => 'validation',
        'page' => 'cs-validation',
    ]);
    Route::view('/payments', 'cs.payments', [
        'title' => 'Payments',
        'role' => 'customer_service',
        'active' => 'payments',
        'page' => 'cs-payments',
    ]);
    Route::view('/pickup', 'cs.pickup', [
        'title' => 'Pickup Schedule',
        'role' => 'customer_service',
        'active' => 'pickup',
        'page' => 'cs-pickup',
    ]);
    Route::view('/history', 'cs.history', [
        'title' => 'Order History',
        'role' => 'customer_service',
        'active' => 'history',
        'page' => 'cs-history',
    ]);
});
