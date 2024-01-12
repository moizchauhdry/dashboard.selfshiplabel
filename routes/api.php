<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\DataController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('rates', [RateController::class, 'index']);
Route::post('data', [DataController::class, 'index']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('addresses', [DataController::class, 'addresses']);
    Route::post('address/store', [AddressController::class, 'store']);
    Route::post('package/index', [PackageController::class, 'index']);
    Route::post('package/get-package', [PackageController::class, 'getPackage']);
    Route::post('package/set-rate', [PackageController::class, 'setRate']);
    Route::post('package/update-rate', [PackageController::class, 'updateRate']);
    Route::post('package/set-address', [PackageController::class, 'setAddress']);
    Route::post('package/set-custom', [PackageController::class, 'setCustom']);
    Route::post('package/payment', [PackageController::class, 'payment']);
    // Route::post('package/payment-charge-later', [PackageController::class, 'chargeLater']);
});
