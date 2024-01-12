<?php

namespace App\Listeners;

use App\Events\ShoppingCreatedEvent;
use App\Mail\UserGeneralMail;
use App\Models\User;
use App\Notifications\ShoppingCreatedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShoppingCreatedListener
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
     * @param  ShoppingCreated  $event
     * @return void
     */
    public function handle(ShoppingCreatedEvent $event)
    {
        $admins = User::whereIn('type',['admin','manager'])->get();
        Notification::send($admins, new ShoppingCreatedNotification($event->order));

        $user = $event->order->customer;
        $data = [
            'subject' => 'Shopping Item Created',
            'name' => $user->name,
            'description' => '<p> shopping list "'.$event->order->id.'" created and sent to admin </p>',
        ];

        try{
            Mail::to($user)->send(new UserGeneralMail($data));
        }catch(\Throwable $e){
            \Log::info($e);
        }
    }
}
