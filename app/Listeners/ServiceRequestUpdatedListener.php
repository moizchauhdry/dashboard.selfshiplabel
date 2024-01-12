<?php

namespace App\Listeners;

use App\Events\ServiceRequestUpdatedEvent;
use App\Mail\UserGeneralMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Notifications\ServiceRequestUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ServiceRequestUpdatedListener
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
    public function handle(ServiceRequestUpdatedEvent $event)
    {
        $service_request = $event->service_request;
        $user = User::find($service_request->package->customer_id);

        if ($user) {
            $user->notify(new ServiceRequestUpdatedNotification($service_request));
        }

        $service = $event->service_request->service;
        $package = $event->service_request->package;

        $data = [
            'subject' => 'Service Respond',
            'name' => $user->name,
            'description' => '<p>Admin has Responded to "'.$service->title.'" request of package ID #'.$package->id.'</p>',
        ];

        try{
            Mail::to($user)->send(new UserGeneralMail($data));
        }catch(\Throwable $e){
            \Log::info($e);
        }
        
    }
}
