<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\Order\OrderItemsController;
use App\Http\Controllers\Api\Order\OrderPaymentTypesController;
use App\Http\Controllers\Api\Order\OrderStatusesController;
use App\Http\Controllers\Api\OrderController as CrmOrderController;
use App\Http\Controllers\Api\Mobile\DeliveryController;
use App\Http\Controllers\Api\Mobile\MeController;
use App\Http\Controllers\Api\Olap\DeliveriesOlapController;
use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\Product\CategoriesController;
use App\Http\Controllers\Api\Product\MainImageController;
use App\Http\Controllers\Api\Product\TypesController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\Order\OrderController as BaseOrderController;
use App\Http\Controllers\Api\Mobile\OrderController;
use App\Http\Controllers\Api\Order\OrderTypesController;

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

    Route::apiResource('clients', ClientController::class)->only(['index',]);
    Route::get('/sources', [ClientController::class, 'sources']);

    Route::apiResource('orders', BaseOrderController::class)->only(['index','store','update']);
    Route::get('/order-types', [OrderTypesController::class, 'index']);
    Route::get('/order-statuses', [OrderStatusesController::class, 'index']);
    Route::get('/order-payment-types', [OrderPaymentTypesController::class, 'index']);
    Route::post('/orders/{id}/set-in-process', [BaseOrderController::class, 'setInProcess']);

    Route::apiResource('order-items', OrderItemsController::class)->only(['index','update']);
    Route::get('/order-items-statuses', [OrderItemsController::class, 'statuses']);

    Route::apiResource('kitchens', KitchenController::class)->only(['index',]);

    Route::apiResource('users', UserController::class)->except(['destroy',]);

    Route::get('/orders/{iikoId}/courier', [CrmOrderController::class, 'courier']);

    Route::get('/products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->only(['index','update','show']);
    Route::post('/{restaurant}/products', [ProductController::class, 'massStore']);

    Route::resource('products.main-image', MainImageController::class);

    Route::apiResource('product-categories', CategoriesController::class)->only(['index',]);
    Route::apiResource('product-types', TypesController::class)->only(['index',]);

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
