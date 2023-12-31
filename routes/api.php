<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'v1',
], function (): void {
    Route::group([
        'prefix' => 'admin',
    ], function (): void {
        Route::post('login', [AdminController::class, 'login']);
        Route::get('logout', [AdminController::class, 'logout'])->middleware('auth:api');

        Route::group([
            'middleware' => ['auth:api', 'admin'],
        ], function (): void {
            Route::get('user-listing', [AdminController::class, 'userListing']);
        });
    });

    Route::group([
        'prefix' => 'order',
        'middleware' => 'auth:api',
    ], function (): void {
        Route::post('create', [OrderController::class, 'create']);
        Route::get('{uuid}', [OrderController::class, 'show']);
        Route::put('{uuid}', [OrderController::class, 'update']);
        Route::delete('{uuid}', [OrderController::class, 'destroy'])->middleware('admin');
    });

    Route::group([
        'prefix' => 'orders',
        'middleware' => ['auth:api','admin'],
    ], function (): void {
        Route::get('/', [OrderController::class, 'index']);
    });

    Route::group([
        'prefix' => 'user',
    ], function (): void {
        Route::post('login', [UserController::class, 'login']);
        Route::get('logout', [UserController::class, 'logout'])->middleware('auth:api');
        Route::group([
            'middleware' => 'auth:api',
        ], function (): void {
            Route::get('/', [UserController::class, 'show']);
            Route::get('orders', [UserController::class, 'orders']);
        });
    });

    Route::group([
        'prefix' => 'product',
    ], function (): void {
        Route::post('create', [ProductController::class, 'create'])->middleware('admin');
    });

    Route::group([
        'prefix' => 'payment',
        'middleware' => 'auth:api',
    ], function (): void {
        Route::post('create', [PaymentController::class, 'create']);
    });

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('order-statuses', [OrderStatusController::class, 'index']);
    Route::get('products', [ProductController::class, 'index']);
});
