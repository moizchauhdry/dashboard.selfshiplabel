<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::prefix('payment')->group(function () {
    Route::any('setup', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('pay', [PaymentController::class,  'pay'])->name('payment.pay');
    Route::any('paypal/init', [PaymentController::class,  'payPalInit'])->name('payment.payPalInit');
    Route::any('payPalSuccess', [PaymentController::class,  'payPalSuccess'])->name('payment.payPalSuccess');
    Route::any('list', [PaymentController::class,  'getPayments'])->name('payments.getPayments');
    Route::get('PaymentSuccess/{id}', [PaymentController::class,  'PaymentSuccess'])->name('payments.PaymentSuccess');
    Route::post('check/coupon', [PaymentController::class, 'checkCoupon'])->name('checkCoupon');
    Route::get('invoice/{id}', [PaymentController::class, 'invoice'])->name('payment.invoice');
    Route::get('generateReport/{id}', [PaymentController::class, 'generateReport'])->name('generateReport');
    Route::any('generateReportList', [PaymentController::class, 'generateReportList'])->name('generateReportList');
    Route::post('add', [PaymentController::class, 'add_payment'])->middleware(['auth'])->name('payment.add');
    // Route::post('stripe-charge-later', [PaymentController::class, 'stripeChargeLater'])->middleware(['auth'])->name('payment.stripe-charge-later');
    Route::post('square-charge-later', [PaymentController::class, 'squareChargeLater'])->middleware(['auth'])->name('payment.square-charge-later');
});
