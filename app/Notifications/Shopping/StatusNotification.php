<?php

namespace App\Notifications\Shopping;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class StatusNotification extends Notification
{
    use Queueable;
    public $status, $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($status, $order)
    {
        $this->status = $status;
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->status === 'approved') {
            $message = 'The order has been approved. Click the "Pay Now" button to proceed with your process.';
            $url = config('app.url') . '/payment/setup?payment_module=order&payment_module_id=' . $this->order->id;

            return (new MailMessage)
                ->subject('Order Approved')
                ->greeting('Dear Customer,')
                ->line($message)
                ->action('Pay Now', $url)
                ->line('Thank you for using our application!');
        }

        if ($this->status === 'rejected') {
            $message = 'The order has been rejected. Click the button below to check your order.';
            return (new MailMessage)
                ->subject('Order Rejected')
                ->greeting('Dear Customer,')
                ->line($message)
                ->action('Click Here', URL::route('shop-for-me.edit', $this->order->id))
                ->line('Thank you for using our application!');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
