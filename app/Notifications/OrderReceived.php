<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReceived extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
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
        //return ['mail','database'];
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
                    ->subject('Order Received by '.config('app.name'))
                    ->line('We have received your order. ')
                    ->line('Tracking Number : '.$this->order->tracking_number_in)
                    ->line('Order Id '.$this->order->id)
                    ->action('Check your order', url('/orders/'.$this->order->id))
                    ->line('Thank your for using '.config('app.name').'!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = 'Your <a class="link-primary" href="'.route("packages.show", ["id" => $this->order->package_id]).'" >order </a> from <strong>'.$this->order->received_from.'</strong> has been received at ware house <strong>'.$this->order->warehouse->name.'</strong>';

        return [
            'order_id' => $this->order->id,
            'customer_id' => $this->order->customer_id,
            'package_id' => $this->order->package_id,
            'message' => $message
        ];
    }
}
