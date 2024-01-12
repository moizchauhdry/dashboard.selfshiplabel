<?php

namespace App\Providers;

use App\Events\CustomFormFilled;
use App\Events\OrderChangesAcceptedByCustomerEvent;
use App\Events\OrderChangesByAdminEvent;
use App\Events\OrderCreatedEvent;
use App\Events\OrderUpdatedEvent;
use App\Events\PackageConsolidated;
use App\Events\PackageShipped;
use App\Events\PackageShippingServiceSelected;
use App\Events\PaymentEventHandler;
use App\Events\ServiceRequestedEvent;
use App\Events\ServiceRequestUpdatedEvent;
use App\Events\ShoppingCompletedEvent;
use App\Events\ShoppingCreatedEvent;
use App\Listeners\ChangesAcceptedByCustomerListener;
use App\Listeners\CustomFormFilledListener;
use App\Listeners\OrderChangesAcceptedByCustomerListener;
use App\Listeners\OrderChangesByAdminListener;
use App\Listeners\OrderCreatedListener;
use App\Listeners\OrderUpdatedListener;
use App\Listeners\PackageConsolidatedListener;
use App\Listeners\PackageShippedListener;
use App\Listeners\PackageShippingServiceListener;
use App\Listeners\PaymentListener;
use App\Listeners\ServiceRequstListener;
use App\Listeners\ServiceRequestUpdatedListener;
use App\Listeners\ShoppingCompletedListener;
use App\Listeners\ShoppingCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreatedEvent::class => [
            OrderCreatedListener::class
        ],
        OrderUpdatedEvent::class => [
            OrderUpdatedListener::class
        ],
        ServiceRequestedEvent::class => [
            ServiceRequstListener::class
        ],
        ServiceRequestUpdatedEvent::class => [
            ServiceRequestUpdatedListener::class
        ],
        CustomFormFilled::class => [
            CustomFormFilledListener::class
        ],
        PackageConsolidated::class => [
            PackageConsolidatedListener::class
        ],
        PackageShippingServiceSelected::class => [
            PackageShippingServiceListener::class
        ],
        PackageShipped::class => [
            PackageShippedListener::class
        ],
        ShoppingCreatedEvent::class => [
            ShoppingCreatedListener::class
        ],
        ShoppingCompletedEvent::class => [
            ShoppingCompletedListener::class
        ],
        OrderChangesByAdminEvent::class => [
            OrderChangesByAdminListener::class
        ],
        OrderChangesAcceptedByCustomerEvent::class => [
            ChangesAcceptedByCustomerListener::class
        ],
        PaymentEventHandler::class => [
            PaymentListener::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
