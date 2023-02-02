<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\EclubApiController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider wi thin a grouap which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'user'], function () {
        Route::get('', [UserController::class, 'me']);
        Route::get('stats', [UserController::class, 'weekStats']);
        Route::post('work/start', [UserController::class, 'startWork']);
        Route::post('work/stop', [UserController::class, 'stopWork']);
    });

    Route::post('device-token', [DeviceTokenController::class,'store']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'show'])->whereNumber('order');
    Route::apiResource('user-orders', UserOrderController::class)->except(['destroy']);
    Route::post('user-orders/{userOrder}/cancel', [UserOrderController::class, 'cancel'])->whereNumber('userOrder');
    Route::post('user-orders/{userOrder}/finish', [UserOrderController::class, 'finish'])->whereNumber('userOrder');

    Route::get('compselections', [EclubApiController::class, 'compselections']);
    Route::get('compilations/{id}', [EclubApiController::class, 'getCompilationById']);
    Route::get('products/{id:[0-9]+}', [EclubApiController::class, 'getProductById']);
});

Route::group(['middleware' => 'trustApp'], function () {
    Route::post('orders/allocate', [OrderController::class, 'allocate']);
    Route::put('orders/status', [UserOrderController::class, 'changeStatusCallback']);
});
