<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PackageStorageNotification extends Notification
{
    use Queueable;
    public $package;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($package)
    {
        $this->package = $package;
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
        return (new MailMessage)
            ->greeting('Dear ' . $this->package->customer->name . ',')
            ->line('The package PKG #' . $this->package->id . ' has exceeded 75 days, and you have been charged for it.')
            ->line('Total Days: ' . $this->package->storage_days . ' | ' . 'Days Exceeded: ' . $this->package->storage_days_exceeded . ' | ' . 'Storage Charges: $' . $this->package->storage_fee)
            ->line('Note: The storage fee is added when a package exceeds 75 days, and after 80 days, the package is terminated or destroyed.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $url = route("packages.show", ["id" => $this->package->id]);

        return [
            'message' => '<a href="' . $url . '">The package <b> PKG #' . $this->package->id . '</b> has exceeded 75 days, and you have been charged for it.</a>',
            'url' => $url
        ];
    }
}
