<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Seller\OrdersController as SellerOrdersController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Middleware\IsSellerMiddleware;

Route::get('/payment/success', [PaymentController::class, 'paymentSuccess']);

// Route::get('/payment/fail', function() {
//     return view('payment.fail');
// });

// Route::get('/payment/cancel', function() {
//     return view('payment.cancel');
// });

Route::group(['prefix'=>'auth','as'=>'auth.'], function() {
    Route::get('login', [LoginController::class, 'loginPage'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('postLogin');

    Route::middleware('auth')->post('logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', IsSellerMiddleware::class])->prefix('seller')->group(function () {
    Route::get('/', function() {
        return view('sellers.dashboard');
    })->name('seller.dashboard');

    Route::resource('product', ProductController::class);
    Route::name('seller')->resource('orders', OrderController::class);

});


/**
 * Register seller
 */
Route::get('account/register-seller', [SellerController::class, 'registerAccountPage']);
Route::post('account/register-seller', [SellerController::class, 'registerAccount']);

/**
 * Admin page, where acc, etc managed here ;)
 */
Route::prefix('admin')->group(function() {
    Route::prefix('manage')->middleware(['auth', IsAdminMiddleware::class])->group(function() {
        Route::get('/', function() {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/account', function() {
            $user = auth()->user();

            return view('admin.account', [
                'user' => $user
            ]);
        })->name('profile');

        Route::get('uploads', [UploadController::class, 'index'])->name('uploads.index');
        Route::get('uploads/{id}', [UploadController::class, 'show'])->name('uploads.show');
        Route::delete('uploads/{id}', [UploadController::class, 'destroy'])->name('uploads.destroy');

        Route::resource('orders', OrdersController::class);
        Route::resource('sellers', SellerController::class);
        Route::resource('users', UserController::class);
        // Route::prefix('users')->group(function() {
        //     Route::get('/', [UserController::class, 'index']);
        //     Route::get('/{id}', [UserController::class, 'show']);

        //     Route::get('/create', [UserController::class, 'create']);
        //     Route::post('/create', [UserController::class, 'store']);

        //     Route::get('/{id}/edit', [UserController::class, 'edit']);
        //     Route::put('/{id}', [UserController::class, 'update']);

        //     Route::delete('/{id}', [UserController::class, 'destroy']);
        // });
    });
});
