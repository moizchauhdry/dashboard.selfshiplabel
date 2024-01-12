<?php

namespace App\Listeners;

use App\Events\ServiceRequestedEvent;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\ServiceRequestNotification;

class ServiceRequstListener
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
     * @param  ServiceRequested  $event
     * @return void
     */
    public function handle(ServiceRequestedEvent $event)
    {
        $admins = User::whereIn('type',['admin','manager'])->get();
        Notification::send($admins, new ServiceRequestNotification($event->service_request));
    }
}
