<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidderSelectionNotification extends Notification
{
    use Queueable;
    protected $bid;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bid)
    {
        $this->bid = $bid;
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
        $url = route("auctions.show", ["id" => $this->bid->auction_id]);

        return (new MailMessage)
            ->greeting('Dear Customer!')
            ->line('Congratulation your bid has been selected.')
            ->line('Kindly review the details and proceed with the payment of $' . $this->bid->amount . ' to receive your auction package.')
            ->action('Login & Pay', $url)
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
        $url = route("auctions.show", ["id" => $this->bid->auction_id]);

        return [
            'auction_id' => $this->bid->auction_id,
            'message' => '<a class="link-primary" href="' . $url . '" >Congratulation your bid has been selected. Kindly review the details and proceed with the payment of $' . $this->bid->amount . ' to receive your auction package.</a>',
            'url' => $url
        ];
    }
}
