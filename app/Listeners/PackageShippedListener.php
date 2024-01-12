<?php

namespace App\Listeners;

use App\Events\PackageShipped;
use App\Mail\UserGeneralMail;
use App\Notifications\PackageShippedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PackageShippedListener
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
     * @param  PackageShipped  $event
     * @return void
     */
    public function handle(PackageShipped $event)
    {
        $package = $event->package;
        $customer = $package->customer;

        if ($customer) {
            $customer->notify(new PackageShippedNotification($package));

            $data = [
                'subject' => 'Package Shipped',
                'name' => $customer->name,
                'description' => '<p> Package ID #"'.$event->package->id.'" has been shipped</p>',
            ];

            try{
                Mail::to($user)->send(new UserGeneralMail($data));
            }catch(\Throwable $e){
                \Log::info($e);
            }
        }
    }
}
