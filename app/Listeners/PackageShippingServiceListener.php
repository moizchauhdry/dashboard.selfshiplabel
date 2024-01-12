<?php

namespace App\Listeners;

use App\Events\PackageShippingServiceSelected;
use App\Models\User;
use App\Notifications\PackageShippingServiceNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PackageShippingServiceListener
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
     * @param  PackageShippingServiceSelected  $event
     * @return void
     */
    public function handle(PackageShippingServiceSelected $event)
    {
        $admins = User::whereIn('type',['admin','manager'])->get();
        Notification::send($admins, new PackageShippingServiceNotification($event->package));
    }
}
