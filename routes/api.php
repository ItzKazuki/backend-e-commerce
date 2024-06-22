<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsSellerMiddleware;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Seller\OrdersController as SellerOrdersController;
use App\Http\Controllers\Api\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Api\User\AccountController;
use App\Models\User;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/**
 * Route utama
 * /v1/products ---> list all products
 * /v1/user ----> info account
 * /v1/orders -----> process orders from json data.
 * /v1/payment ----> select payment
 * /v1/seller/* -------> seller create remove etc
 */

 // versioning path
Route::prefix('v1')->middleware('api')->group(function () {
    /**
     * Auth Routes, for authentication process
     *
     * return bearer token.
     */
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
        Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')->get('user', [AccountController::class, 'index']);
    Route::middleware('auth:sanctum')->get('seller/{id}', function(string $id) {
        return User::where('role', User::SELLER)->findOrFail($id);
    });

    /**
     * List Products, for user can get all products and can be spesific by query in url
     *
     * return object list products
     */
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    /**
     * List orders, user can find your order and can process order
     *
     * return object list order
     */
    Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [OrdersController::class, 'index']);
        Route::get('/{id}', [OrdersController::class, 'show']);
        Route::post('/create', [OrdersController::class, 'store']);
    });

    /**
     * Payment process, this using for payment proces for callback, etc..
     *
     */
    Route::prefix('payment')->middleware('auth:sanctum')->group(function () {
        // callback here!
        // payment process here!
    });

    /**
     * Seller Dashboard, this using for seller managed orders and keuntungan.
     */
    Route::prefix('seller')->middleware([IsSellerMiddleware::class, 'auth:sanctum'])->group(function () {
        Route::prefix('products')->group(function () {
            Route::post('/create', [SellerProductController::class, 'store']);
            Route::put('/{id}', [SellerProductController::class, 'update']);
            Route::delete('/{id}', [SellerProductController::class, 'destroy']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [SellerOrdersController::class, 'index']);
            Route::get('/{id}', [SellerOrdersController::class, 'show']);
            Route::put('/{id}', [SellerOrdersController::class, 'update']);
        });
    });
});
