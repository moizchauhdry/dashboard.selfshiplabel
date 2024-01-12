<?php

namespace App\Listeners;

use App\Events\OrderChangesByAdminEvent;
use App\Mail\UserGeneralMail;
use App\Notifications\NotifyChangesByAdminToCustomer;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OrderChangesByAdminListener
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
    public function handle(OrderChangesByAdminEvent $event)
    {
        $order = $event->order;
        $user = $order->customer;
        if ($user) {
            $user->notify(new NotifyChangesByAdminToCustomer($order));
        }

        $user = $event->order->customer;
        $data = [
            'subject' => 'Changes in Shopping List',
            'name' => $user->name,
            'description' => '<p> Admin had updated your shopping item "'.$event->order->id.'"</p>',
        ];

        try{
            Mail::to($user)->send(new UserGeneralMail($data));
        }catch(\Throwable $e){
            \Log::info($e);
        }
    }
}
