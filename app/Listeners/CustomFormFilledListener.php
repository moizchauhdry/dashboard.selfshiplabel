<?php

namespace App\Listeners;

use App\Events\CustomFormFilled;
use App\Models\User;
use App\Notifications\CustomFormFilledNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CustomFormFilledListener
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
    public function handle(CustomFormFilled $event)
    {
        $admins = User::where(['type' => 'admin'])->get();
        Notification::send($admins, new CustomFormFilledNotification($event->package));
    }
}
