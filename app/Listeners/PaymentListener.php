<?php

namespace App\Listeners;

use App\Events\PaymentEventHandler;
use App\Models\User;
use App\Notifications\PaymentNotification;
use App\Mail\UserGeneralMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PaymentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentEventHandler $event)
    {
        $payment = $event->payment;
        $admins = User::whereIn('type', ['admin', 'manager'])->get();
        Notification::send($admins, new PaymentNotification($payment));

        $user = $payment->customer;
        $data = [
            'subject' => ($payment->package_id != null ? 'Package Payment' : ($payment->order_id != null ? 'Order Payment' : 'Payment')),
            'name' => $user->name,
            'description' => '<p> You have been charged $' . $payment->charged_amount . ' for ' . ($payment->package_id != null ? 'Package' : 'Order') . ' ID #' . ($payment->package_id != null ? $payment->package_id : $payment->order_id) . '  </p>',
            'attachment' => $payment->invoice_url,
        ];

        Mail::to($user)->send(new UserGeneralMail($data));
    }
}
