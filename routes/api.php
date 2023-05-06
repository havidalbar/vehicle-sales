<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SalesController;


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

Route::get('/', function () {
//     return view('welcome');
});

Route::post('register', [UserController::class, 'register'])->name('api.register');
Route::post('login', [UserController::class, 'login'])->name('api.login');
  
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('user', [UserController::class, 'getAuthenticatedUser'])->name('api.info');
    Route::post('logout', [UserController::class, 'logout'])->name('api.logout');

    Route::post('vehicle', [VehicleController::class, 'registerVehicle'])->name('api.vehicle');
    Route::get('vehicle/{id}', [VehicleController::class, 'getVehicleById'])->name('api.vehicle.id');
    Route::get('quota-vehicle', [VehicleController::class, 'getAllQuotaVehicle'])->name('api.quota.vehicle');
    Route::get('leftover-quota-vehicle', [VehicleController::class, 'getLeftoverQuotaVehicle'])->name('api.leftover.quota.vehicle');

    Route::post('order', [SalesController::class, 'orderVehicle'])->name('api.order');
    Route::get('order', [SalesController::class, 'getAllOrderVehicle'])->name('api.order');
    Route::get('order/sold', [SalesController::class, 'getVehicleSold'])->name('api.order.sold');
    Route::get('order/{id}', [SalesController::class, 'getOrderVehicleById'])->name('api.order.id');

});