<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CourierController;
use App\Http\Controllers\Api\Mobile\DeliveryController;
use App\Http\Controllers\Api\Mobile\MeController;
use App\Http\Controllers\Api\Olap\DeliveriesOlapController;
use App\Http\Controllers\Api\OlapController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\Mobile\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('rbac')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
    });

    Route::apiResource('locations', LocationController::class)->only(['index',]);

    Route::apiResource('cities', CityController::class)->only(['index',]);

    Route::apiResource('users', UserController::class)->except(['destroy',]);

    Route::get('couriers/{iikoId}/coordinates', [CourierController::class, 'coordinates']);

    Route::get('/products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->only(['index','update','show']);
    Route::post('/{restaurant}/products', [ProductController::class, 'massStore']);

    Route::prefix('mobile')->group(function () {

        Route::apiResource('orders', OrderController::class)->only(['index', 'update']);
        Route::apiResource('deliveries', DeliveryController::class)->only(['store']);

        Route::prefix('me')->group(function () {
            Route::put('/coordinates', [MeController::class, 'updateCoordinates']);
        });
    });

    Route::prefix('olap')->group(function () {
        Route::get('/deliveries', [DeliveriesOlapController::class, 'index']);
    });
});
