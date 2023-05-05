<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
  
});