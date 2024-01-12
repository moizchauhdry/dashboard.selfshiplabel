<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderUpdated extends Notification
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
            ->subject('Order status updated by '.config('app.name'))
            ->line('Admin has updated your order status to <strong>' . strtoupper($this->order->status) . '</strong> , here are the details. ')
            ->line('Tracking Number : '.$this->order->tracking_number_in)
            ->line('Order Id '.$this->order->id)
            ->action('Check your order', url('/orders/'.$this->order->order_id))
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
        $url = route("packages.show", ["id" => $this->order->package_id]);
        $message = 'Your <a class="link-primary" href="'.$url.'" >order </a> from <strong>'.$this->order->received_from.'</strong> Status has changed to <strong>'.$this->order->package->status.'</strong>';

        return [
            'order_id' => $this->order->id,
            'customer_id' => $this->order->customer_id,
            'package_id' => $this->order->package_id,
            'message' => $message,
            'url' => $url,
        ];
    }
}