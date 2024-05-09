<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\DataController;
use App\Http\Controllers\API\InquiryController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\SquarePaymentController;
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
    Route::post('profile', [DataController::class, 'profile']);
    Route::post('get-address', [DataController::class, 'getAddress']);
    Route::post('addresses', [DataController::class, 'addresses']);
    Route::post('address/store', [AddressController::class, 'store']);
    Route::post('package/index', [PackageController::class, 'index']);
    Route::post('package/get-package', [PackageController::class, 'getPackage']);
    Route::post('package/set-rate', [PackageController::class, 'setRate']);
    Route::post('package/update-rate', [PackageController::class, 'updateRate']);
    Route::post('package/set-address', [PackageController::class, 'setAddress']);
    Route::post('package/set-custom', [PackageController::class, 'setCustom']);
    Route::post('package/payment', [SquarePaymentController::class, 'payment']);
    Route::post('package/square-payment', [SquarePaymentController::class, 'index']);

    Route::post('inquiry/list', [InquiryController::class, 'list']);
    Route::post('inquiry/create', [InquiryController::class, 'create']);
    Route::post('inquiry/message/send', [InquiryController::class, 'messageSend']);
    Route::post('inquiry/message/list', [InquiryController::class, 'messageList']);
});

// Project ID: 2
Route::middleware('auth.basic')->group(function () {
    Route::post('v2/rates', [RateController::class, 'index2']);
    Route::post('v2/package', [PackageController::class, 'createPackageForExternal']);
    Route::post('v2/data', [DataController::class, 'index2']);
});
