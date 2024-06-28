<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsSellerMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Payments\PaymentController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\NotificationsController as SellerNotificationsController;
use App\Http\Controllers\Admin\NotificationsController as AdminNotificationsController;

Route::get('app/health', HealthCheckResultsController::class);

Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
    Route::get('wait-confirm', [PaymentController::class, 'waitConfirm'])->name('wait-confirm');
    Route::get('process', [PaymentController::class, 'process'])->name('process');
    Route::get('success', [PaymentController::class, 'success'])->name('success');
    Route::get('pending', [PaymentController::class, 'pending'])->name('pending');
    Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('error', [PaymentController::class, 'error'])->name('error');
});

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::get('login', [LoginController::class, 'loginPage'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('postLogin');

    Route::middleware('auth')->post('logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => 'seller', 'as' => 'seller.', 'middleware' => ['auth', IsSellerMiddleware::class]], function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [SellerNotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [SellerNotificationsController::class, 'show'])->name('notifications.show');

    Route::resource('product', SellerProductController::class);
    Route::resource('orders', SellerOrderController::class);
});

/**
 * Register seller
 */
Route::get('account/register-seller', [AdminSellerController::class, 'registerAccountPage']);
Route::post('account/register-seller', [AdminSellerController::class, 'registerAccount']);

/**
 * Admin page, where acc, etc managed here ;)
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', IsAdminMiddleware::class]], function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

    Route::get('/account', fn () => view('admin.account', ['user' => auth()->user()]))->name('profile');

    Route::get('/notifications', [AdminNotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [AdminNotificationsController::class, 'show'])->name('notifications.show');

    Route::get('uploads', [AdminUploadController::class, 'index'])->name('uploads.index');
    Route::get('uploads/{id}', [AdminUploadController::class, 'show'])->name('uploads.show');
    Route::delete('uploads/{id}', [AdminUploadController::class, 'destroy'])->name('uploads.destroy');

    Route::resource('orders', AdminOrdersController::class);
    Route::resource('sellers', AdminSellerController::class);
    Route::resource('users', AdminUserController::class);
});
