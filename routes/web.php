<?php

use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsSellerMiddleware;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrdersController as AdminOrdersController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;

Route::group(['prefix'=>'payment', 'as'=>'payment.'], function() {
    Route::get('wait-confirm', [PaymentController::class, 'waitConfirm'])->name('wait-confirm');
    Route::get('process', [PaymentController::class, 'process'])->name('process');
    Route::get('success', [PaymentController::class, 'success'])->name('success');
    Route::get('pending', [PaymentController::class, 'pending'])->name('pending');
    Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
});

Route::group(['prefix'=>'auth','as'=>'auth.'], function() {
    Route::get('login', [LoginController::class, 'loginPage'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('postLogin');

    Route::middleware('auth')->post('logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::group(['prefix'=>'seller', 'middleware' => ['auth', IsSellerMiddleware::class]],function () {
    Route::get('/', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

    Route::resource('product', SellerProductController::class);
    Route::name('seller')->resource('orders', SellerOrderController::class);
});

/**
 * Register seller
 */
Route::get('account/register-seller', [AdminSellerController::class, 'registerAccountPage']);
Route::post('account/register-seller', [AdminSellerController::class, 'registerAccount']);

/**
 * Admin page, where acc, etc managed here ;)
 */
Route::prefix('admin')->group(function() {
    Route::prefix('manage')->middleware(['auth', IsAdminMiddleware::class])->group(function() {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        Route::get('/account', fn() => view('admin.account', ['user' => auth()->user()]))->name('profile');

        Route::get('uploads', [AdminUploadController::class, 'index'])->name('uploads.index');
        Route::get('uploads/{id}', [AdminUploadController::class, 'show'])->name('uploads.show');
        Route::delete('uploads/{id}', [AdminUploadController::class, 'destroy'])->name('uploads.destroy');

        Route::resource('orders', AdminOrdersController::class);
        Route::resource('sellers', AdminSellerController::class);
        Route::resource('users', AdminUserController::class);
    });
});
