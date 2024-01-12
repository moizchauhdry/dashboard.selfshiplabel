<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Models\User;
use App\Notifications\OrderUpdated;
use App\Notifications\SendUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderStatusChangedListener
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
     * @param  OrderStatusChanged  $event
     * @return void
     */
    public function handle(OrderStatusChanged $event) {
        $order = $event->order;
        $user = User::find($order->customer_id);
        if ($user) {
            $user->notify(new SendUserNotification($order, 'Admin has changed the order status to '. $order->status));
            $order->notify(new OrderUpdated($order));
        }
    }
}
