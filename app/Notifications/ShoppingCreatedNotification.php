<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShoppingCreatedNotification extends Notification
{
    use Queueable;

    public $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $url = route("shop-for-me.show", ["id" => $this->order->id]);
        $customer = $this->order->customer;
        $customerDetailURL = '<a href="'.route('customers.show',$customer->id).'">'.$customer->name_with_suite_no.'</a>' ?? '';

        return [
            'order_id' => $this->order->id,
            'message' => 'Customer <strong>'.$customerDetailURL.'</strong> has added order to Shopping List <a class="link-primary" href="'.$url.'" >See Details</a>',
            'url' => $url
        ];
    }
}
