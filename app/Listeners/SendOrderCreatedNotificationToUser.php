<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\ParcelReceived;
use App\Notifications\SendUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotificationToUser
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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event) {
        $order = $event->order;
        $user = User::find($order->customer_id);
        if ($user) {
            $user->notify(new SendUserNotification($order, 'Admin has created an order for you.'));
            $order->notify(new ParcelReceived($order));
        }
    }
}
