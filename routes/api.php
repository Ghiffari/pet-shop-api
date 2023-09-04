<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    'prefix' => 'v1'
], function(){
    Route::group([
        'prefix' => 'admin'
    ], function(){
        Route::post('login', [AdminController::class, 'login']);
        Route::get('logout', [AdminController::class, 'logout']);

        Route::group([
            'middleware' => 'auth:api'
        ], function(){
            Route::get('user-listing',[AdminController::class, 'userIndex']);

        });
    });

    Route::group([
        'prefix' => 'user'
    ], function(){
        Route::post('login', [UserController::class, 'login']);
        Route::get('logout', [UserController::class, 'logout']);
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::get('/', [UserController::class, 'show']);
        });
    });
});
