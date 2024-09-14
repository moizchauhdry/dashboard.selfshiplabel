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

    Route::post('fetch-address', [DataController::class, 'fetchAddress']);
    Route::post('fetch-address-list', [DataController::class, 'fetchAddressList']);
    Route::post('address/store', [AddressController::class, 'store']);
    
    Route::post('package/index', [PackageController::class, 'index']);
    // Route::post('package/create', [PackageController::class, 'createPackage']);
    Route::post('package/get-package/{id}', [PackageController::class, 'getPackage']);
    Route::post('package/set-rate', [PackageController::class, 'setRate']);
    Route::post('package/update-rate', [PackageController::class, 'updateRate']);
    Route::post('package/set-address', [PackageController::class, 'setAddress']);
    Route::post('package/set-custom', [PackageController::class, 'setCustom']);
    Route::post('package/update-signature', [PackageController::class, 'updateSignature']);
    Route::post('package/save-as-draft', [PackageController::class, 'saveAsDraft']);
    Route::post('package/pay-later', [PackageController::class, 'payLater']);
    Route::post('package/edit-package', [PackageController::class, 'editPackage']);
    Route::post('package/reship', [PackageController::class, 'reship']);

    Route::post('package/payment', [SquarePaymentController::class, 'payment']);
    Route::post('package/square-payment', [SquarePaymentController::class, 'index']);
    Route::post('package/bulk-payment', [SquarePaymentController::class, 'bulkPayment']);
    // Route::get('package/square-bulk-payment', [SquarePaymentController::class, 'squareBulkPayment']);

    Route::post('inquiry/list', [InquiryController::class, 'list']);
    Route::post('inquiry/fetch', [InquiryController::class, 'fetch']);
    Route::post('inquiry/create', [InquiryController::class, 'create']);
    Route::post('inquiry/message/send', [InquiryController::class, 'messageSend']);
    Route::post('inquiry/message/list', [InquiryController::class, 'messageList']);
    Route::post('inquiry/update/status', [InquiryController::class, 'updateStatus']);
});

// Project ID: 2
Route::middleware('auth.basic')->group(function () {
    Route::post('v2/rates', [RateController::class, 'index2']);
    Route::post('v2/package', [PackageController::class, 'createPackageForExternal']);
    Route::post('v2/data', [DataController::class, 'index2']);
});
