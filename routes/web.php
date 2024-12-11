<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\AuctionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarkupController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShippingCalculatorController;
use App\Http\Controllers\ShippingRatesController;
use App\Http\Controllers\ShippingServiceController;
use App\Http\Controllers\SquarePaymentController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('homePage');

Route::get('/checkAuth-user', [HomeController::class, 'checkAuth']);
Route::any('/dashboard', 'HomeController@dashboard')->name('dashboard')->middleware(['auth', 'verified']);
Route::any('/dashboard-success', 'HomeController@dashboard')->name('dashboard-success')->middleware(['auth', 'verified']);
Route::get('/shipping-calculator', 'HomeController@pricing')->name('shipping-calculator');
Route::get('/getServicesList', 'HomeController@getServicesList')->name('getServicesList');
Route::get('/pricing', 'HomeController@pricingTable')->name('pricing');
Route::get('/getListings', 'HomeController@getListings')->name('getListings');
Route::get('/getQuotes', 'HomeController@getQuotes')->name('getQuotes');
Route::get('/getQuote', 'HomeController@getQuote')->name('getQuote');
Route::get('/getQuoteByOrders', 'HomeController@getQuoteByOrders')->name('getQuoteByOrders');
Route::get('/test-order-xps', 'HomeController@putTestOrder')->name('putTestOrder');
Route::get('/shopping', 'HomeController@shopping')->name('shopping')->middleware('auth');
// Route::get('/test-email', 'EmailController@index')->name('test-email');
Route::any('announcement', [HomeController::class, 'announcement'])->name('announcement');

Route::any('/orders/index', 'OrderController@index')->name('orders')->middleware('auth');
Route::get('/orders/create', 'OrderController@create')->name('orders.create')->middleware('auth');
Route::post('/orders/store', 'OrderController@store')->name('order.store')->middleware('auth');
Route::get('/orders/edit/{id}', 'OrderController@edit')->name('order.edit')->middleware('auth');
Route::post('/orders/update', 'OrderController@update')->name('orders.update')->middleware('auth');
Route::get('/orders/show/{id}', 'OrderController@show')->name('orders.show')->middleware('auth');
Route::post('/orders/removeItem', 'OrderController@removeItem')->name('orders.removeItem')->middleware('auth');
// Route::get('/orders/quote', 'OrderController@quote')->name('orders.quote')->middleware('auth');

require __DIR__ . '/package.php';

Route::get('/services/create', 'ServiceController@create')->name('services.create')->middleware('auth');
Route::post('/services', 'ServiceController@store')->name('services.store')->middleware('auth');
Route::get('/services', 'ServiceController@index')->name('services')->middleware('auth');
Route::get('/services/{id}/edit', 'ServiceController@edit')->name('services.edit')->middleware('auth');
Route::post('/services/update', 'ServiceController@update')->name('services.update')->middleware('auth');
Route::get('/services/{id}', 'ServiceController@show')->name('services.show')->middleware('auth');
Route::delete('/services{id}', 'ServiceController@destroy')->name('services.destroy')->middleware('auth');

Route::get('/profile', 'AccountsContoller@profile')->name('profile')->middleware('auth');
Route::post('/update-profile', 'AccountsContoller@updateProfile')->name('update-profile')->middleware('auth');

Route::get('/addresses', 'AddressController@index')->name('addresses')->middleware('auth');
Route::get('/address/create', 'AddressController@create')->name('address.create')->middleware('auth');
Route::post('/address', 'AddressController@store')->name('address.store')->middleware('auth');
Route::get('/address/{id}/edit', 'AddressController@edit')->name('address.edit')->middleware('auth');
Route::put('/address', 'AddressController@update')->name('address.update')->middleware('auth');
Route::delete('/address{id}', 'AddressController@destroy')->name('address.destroy')->middleware('auth');
Route::get('/address/suite', 'AddressController@suite')->name('address.suite')->middleware('auth');

Route::get('/warehouses', 'WarehouseController@index')->name('warehouses')->middleware('auth');
Route::get('/warehouses/create', 'WarehouseController@create')->name('warehouses.create')->middleware('auth');
Route::post('/warehouses', 'WarehouseController@store')->name('warehouses.store')->middleware('auth');
Route::get('/warehouses/{id}/edit', 'WarehouseController@edit')->name('warehouses.edit')->middleware('auth');
Route::post('/warehouses', 'WarehouseController@update')->name('warehouses.update')->middleware('auth');
Route::delete('/warehouses{id}', 'WarehouseController@destroy')->name('warehouses.destroy')->middleware('auth');

Route::group(['prefix' => 'auctions-a', 'middleware' => 'auth', 'as' => 'auctions.'], function () {
    Route::get('listing', [AuctionController::class, 'listing'])->name('listing');
    Route::get('create', [AuctionController::class, 'create'])->name('create');
    Route::post('store', [AuctionController::class, 'store'])->name('store');
    Route::get('show/{id}', [AuctionController::class, 'show'])->name('show');
    Route::get('edit/{id}', [AuctionController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [AuctionController::class, 'update'])->name('update');
    Route::post('delete-image', [AuctionController::class, 'deleteImage'])->name('delete-image');
    Route::post('select-bid', [AuctionController::class, 'selectBidder'])->name('select-bid');
    Route::post('update-status', [AuctionController::class, 'updateStatus'])->name('update-status');
});

Route::get('/settings', 'SettingsController@index')->name('settings')->middleware('auth');
Route::post('/settings', 'SettingsController@update')->name('settings.update')->middleware('auth');

Route::group(['prefix' => 'shipping-services', 'middleware' => 'auth', 'as' => 'shipping-services.'], function () {
    Route::get('/', [ShippingServiceController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [ShippingServiceController::class, 'edit'])->name('edit');
    Route::post('/update', [ShippingServiceController::class, 'update'])->name('update');
});

Route::group(['prefix' => 'inquirie', 'middleware' => 'auth', 'as' => 'inquirie.'], function () {
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    Route::get('/fetch/{user_id}/{track_id}', [InquiryController::class, 'fetch'])->name('fetch');
    Route::post('inquiry/fetch', [InquiryController::class, 'listfetch'])->name('list-fetch');
    Route::post('/message/send', [InquiryController::class, 'messageSend'])->name('message-send');
    Route::post('/message/list', [InquiryController::class, 'messageList'])->name('message-list');
});

Route::get('/contact-customer-service', 'ContactUs@index')->name('contact-customer-service');

Route::get('/update-password', 'AccountsContoller@edit')->middleware(['auth', 'verified'])->name('update-password');
Route::post('/update-password', 'AccountsContoller@update')->middleware(['auth', 'verified'])->name('save-updated-password');


Route::get('/test', function () {
    return Inertia::render('test');
})->middleware(['auth', 'verified'])->name('test');

require __DIR__ . '/auth.php';

Route::get('/manage-users', [CustomerController::class, 'users'])->middleware(['auth', 'verified'])->name('manage-users');
Route::get('/create-users', [CustomerController::class, 'createUser'])->middleware(['auth', 'verified'])->name('create-users');
Route::post('/save-users', [CustomerController::class, 'saveUser'])->middleware(['auth', 'verified'])->name('save-users');
Route::get('/edit-users/{id}', [CustomerController::class, 'editUser'])->middleware(['auth', 'verified'])->name('edit-users');
Route::post('/update-users', [CustomerController::class, 'updateUser'])->middleware(['auth', 'verified'])->name('update-users');
Route::get('/show-pdf', [CustomerController::class, 'showPDF'])->name('show-pdf');
Route::delete('/delete-users/{id}', [CustomerController::class, 'deleteUser'])->middleware(['auth', 'verified'])->name('delete-users');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('customer')->group(function () {
        Route::any('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::post('update/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::get('show/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::post('account-type/update', [CustomerController::class, 'updateAccountType'])->name('customers.account-type.update');

        Route::get('/markup/{customer_id}', [CustomerController::class, 'markup'])->name('customers.markup');
        Route::post('/markup/update', [CustomerController::class, 'updateMarkup'])->name('customers.markup-update');
    });
});

Route::get('pages/list', 'CMSPageController@index')->middleware(['auth', 'verified'])->name('pages_list');
Route::get('pages/edit/{id}', 'CMSPageController@edit')->middleware(['auth', 'verified'])->name('page_edit');
Route::post('pages/update', 'CMSPageController@update')->middleware(['auth', 'verified'])->name('page_update');
Route::get('pages/add', 'PostController@add')->middleware(['auth', 'verified'])->name('page_new');
Route::post('pages/save', 'PostController@save')->middleware(['auth', 'verified'])->name('page_save');
Route::get('page/{slug}', 'CMSPageController@show')->name('page-show');
Route::get('packages-to-dash/{id}', 'PackageController@pushPackage')->name('pushPackage');

Route::get('dashboard/shipping-calculator', [ShippingCalculatorController::class, 'index'])->name('dashboard.shipping-calculator.index');
Route::any('shipping-rates', [ShippingRatesController::class, 'index'])->name('shipping-rates.index');
Route::get('auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('auctions/{id}', [AuctionController::class, 'detail'])->name('auctions.detail');
Route::post('bid-auction', [AuctionController::class, 'bid'])->name('auctions.bid');

// SELF SERVICE ROUTES
Route::get('decode-label/{id}', [HomeController::class, 'decodeLabel']);
Route::get('stripe/{client_secret}', [HomeController::class, 'stripe']);
Route::get('stripe-success', [HomeController::class, 'stripeSuccess']);

// SQUARE PAYMENT

Route::get('square-payment/{id}', [SquarePaymentController::class, 'index'])->name('package.square-payment');
Route::post('square-payment-success', [SquarePaymentController::class, 'squarePaymentSuccess'])->name('package.square-payment.success');
Route::get('square-payment-complete', [SquarePaymentController::class, 'complete'])->name('square.complete');
Route::get('decode-pdf', [HomeController::class, 'decodePdf']);

Route::get('square-bulk-payment', [SquarePaymentController::class, 'squareBulkPayment']);
Route::post('square-bulk-payment-success', [SquarePaymentController::class, 'squareBulkPaymentSuccess'])->name('package.square-bulk-payment.success');
