<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftCardApprovalNotification extends Notification
{
    use Queueable;
    public $gift_card;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($gift_card)
    {
        $this->gift_card = $gift_card;
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
        $name = 'Dear ' . $this->gift_card->user->name . ',';

        if ($this->gift_card->status == 'Accepted') {
            $msg = 'Your gift card request has been approved by ShippingXPS. Kindly review the details and proceed with the payment of $' . $this->gift_card->final_amount . ' to receive your cards.';
        } else {
            $msg = 'Your gift card request has been rejected by ShippingXPS.';
        }

        return (new MailMessage)
            ->greeting($name)
            ->subject('Gift Card')
            ->line($msg)
            ->line('Gift Card: #' . $this->gift_card->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $url = '<a href="' . route('gift-card.edit', $this->gift_card->id) . '">Gift Card #' . $this->gift_card->id . '</a>';

        if ($this->gift_card->status == 'Accepted') {
            $msg = 'Your gift card request has been approved by ShippingXPS. Kindly review the details and proceed with the payment of $' . $this->gift_card->final_amount . ' to receive your cards.';
        } else {
            $msg = 'Your gift card request has been rejected by ShippingXPS.';
        }

        return [
            'gift_card_id' => $this->gift_card->id,
            'message' => $msg . ' ' . $url,
            'url' => $url
        ];
    }
}
