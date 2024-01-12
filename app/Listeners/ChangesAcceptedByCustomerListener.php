<?php

namespace App\Listeners;

use App\Events\OrderChangesAcceptedByCustomerEvent;
use App\Models\User;
use App\Notifications\NotifyOrderChangesAcceptedByCustomerToAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangesAcceptedByCustomerListener
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
    public function handle(OrderChangesAcceptedByCustomerEvent $event)
    {
        $order = $event->order;
        \Log::info($order);
        $admins = User::whereIn('type',['admin','manager'])->get();
        \Notification::send($admins,new NotifyOrderChangesAcceptedByCustomerToAdmin($order));

    }
}
