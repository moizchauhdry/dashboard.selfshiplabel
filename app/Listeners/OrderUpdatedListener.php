<?php

namespace App\Listeners;

use App\Events\OrderUpdatedEvent;
use App\Mail\PackageMail;
use App\Models\User;
use App\Notifications\OrderUpdated;
use Illuminate\Support\Facades\Mail;

class OrderUpdatedListener
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
    public function handle(OrderUpdatedEvent $event)
    {
        $order = $event->order;
        $user = User::find($order->customer_id);

        $order->notify(new OrderUpdated($order));

        $images = [];
        foreach ($order->images as $key => $image) {
            $images[] = 'https://app.shippingxps.com/public/uploads/' . $image->image;
        }

        $data = [
            'subject' => 'Package Updated - PKG #' . $order->package_id,
            'user_name' => $user->name,
            'package_id' => 'PKG #' . $order->package_id,
            'warehouse' => $order->warehouse->name,
            'dimensions' => $order->package_length . ' x ' . $order->package_width . ' x ' . $order->package_height . ' x ' . $order->dim_unit,
            'weight' => $order->package_weight . ' ' . $order->weight_unit,
            'tracking_number_in' => $order->tracking_number_in,
            'images' => $images,
        ];

        Mail::to($user)->send(new PackageMail($data));
    }
}
