<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::prefix('packages')->group(function () {
        Route::any('index', 'PackageController@index')->name('packages.index');
        Route::post('service-request', 'PackageController@serviceRequest')->name('packages.service-request');
        Route::post('service-handle', 'PackageController@serviceHandle')->name('packages.service-handle');
        Route::post('ship-package', 'PackageController@shipPackage')->name('packages.ship-package');
        Route::post('consolidate-package', 'PackageController@consolidatePackage')->name('packages.consolidate');
        Route::post('set-shipping-service', 'PackageController@setShippingService')->name('packages.set-shipping-service');
        Route::any('consolidation', 'PackageController@consolidation')->name('packages.consolidation');
        Route::post('consolidation/store', 'PackageController@storeConsolidation')->name('packages.consolidation.store');
        Route::any('multipiece', 'PackageController@multipiece')->name('packages.multipiece');
        Route::post('multipiece/store', 'PackageController@storeMultipiece')->name('packages.multipiece.store');
        Route::post('address/update', 'PackageController@updateAddress')->name('packages.address.update');
        Route::post('charges/update', 'PackageController@updateCharges')->name('packages.charges.update');
        Route::post('return-package', 'PackageController@returnPackage')->name('packages.return-package');
        Route::post('coupon', 'PackageController@coupon')->name('packages.coupon');
        Route::post('coupon/remove', 'PackageController@removeCoupon')->name('packages.coupon.remove');
        Route::get('show/{id}', 'PackageController@show')->name('packages.show');
        Route::get('create/{order_id}', 'PackageController@create')->name('package.create');
        Route::post('store', 'PackageController@store')->name('package.store');
        Route::get('edit/{id}/', 'PackageController@edit')->name('package.edit');
        Route::post('update', 'PackageController@update')->name('package.update');
        Route::get('custom/{id}/{mode?}', 'PackageController@custom')->name('packages.custom');
        Route::get('commercial-invoice/{id}', 'PackageController@commercialInvoice')->name('packages.pdf');
        Route::post('destroy', 'PackageController@destroy')->name('packages.destroy');
        Route::post('upload-file', 'PackageController@uploadFile')->name('packages.upload-file');
        Route::post('generate-label', 'PackageController@generateLabel')->name('packages.generate-label');
    });
});
