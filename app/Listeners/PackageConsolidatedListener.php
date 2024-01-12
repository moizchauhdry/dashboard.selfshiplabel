<?php

namespace App\Listeners;

use App\Events\PackageConsolidated;
use App\Mail\UserGeneralMail;
use App\Notifications\PackageConsolidatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PackageConsolidatedListener
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
     * @param  PackageConsolidated  $event
     * @return void
     */
    public function handle(PackageConsolidated $event)
    {
        $package = $event->package;
        $customer = $package->customer;

        if ($customer) {
            $customer->notify(new PackageConsolidatedNotification($package));

            $data = [
                'subject' => 'Package Consolidated',
                'name' => $customer->name,
                'description' => '<p> Package ID #"'.$event->package->id.'" has beed consolidated</p>',
            ];

            try{
                Mail::to($user)->send(new UserGeneralMail($data));
            }catch(\Throwable $e){
                \Log::info($e);
            }
        }
    }
}
