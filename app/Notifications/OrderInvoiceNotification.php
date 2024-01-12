<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\UserGeneralMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OrderInvoiceNotification extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = 'https://app.shippingxps.com/public/uploads/' . $this->order->receipt_url;
        $name = 'Dear ' . $this->order->user->name . ',';

        return (new MailMessage)
            ->greeting($name)
            ->line('The invoice and Tracking number have been uploaded.')
            ->action('Download Invoice', url($url))
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
        $url = '<a href="' . route('shop-for-me.show', $this->order->id) . '"> Order Detail #' . $this->order->id . '</a>';

        return [
            'order_id' => $this->order->id,
            'message' => 'The invoice and tracking number have been uploaded.' . $url,
            'url' => $url
        ];
    }
}
