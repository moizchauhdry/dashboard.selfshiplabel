<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        //
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $customer = $this->payment->customer;
        $customerDetailURL = '<a href="' . route('customers.show', $customer->id) . '">' . $customer->name_with_suite_no . '</a>' ?? '';

        if ($this->payment->package_id != null) {

            $url = route("packages.show", $this->payment->package_id);
            return [
                'package_id' => $this->payment->package->id,
                'message' => 'Customer <strong>' . $customerDetailURL . '</strong> has paid for <strong>Package ' . $this->payment->package->id . '</strong>, See Details  <a class="link-primary" href="' . $url . '" >Package # ' . $this->payment->package->id . '</a>',
                'url' => $url
            ];
        } elseif ($this->payment->order_id != null) {
            $url = route("shop-for-me.edit", $this->payment->order_id);
            return [
                'order_id' => $this->payment->order_id,
                'message' => 'Customer <strong>' . $customerDetailURL . '</strong> has paid for <strong>Order ' . $this->payment->order_id . '</strong>, Click on link to complete shipping <a class="link-primary" href="' . $url . '" >Order # ' . $this->payment->order_id . '</a>',
                'url' => $url
            ];
        } elseif ($this->payment->insurance_id != null) {

            $url = route("insurance.edit", $this->payment->insurance_id);
            return [
                'insurance_id' => $this->payment->insurance_id,
                'message' => 'Customer <strong>' . $customerDetailURL . '</strong> has paid for <strong>Insurance Request ' . $this->payment->insurance_id . '</strong>, Click here link  <a class="link-primary" href="' . $url . '" >Request # ' . $this->payment->insurance_id . '</a>',
                'url' => $url
            ];
        } elseif ($this->payment->additional_request_id != null) {
            $url = route("additional-request.edit", $this->payment->additional_request_id);
            return [
                'additional_request_id' => $this->payment->additional_request_id,
                'message' => 'Customer <strong>' . $customerDetailURL . '</strong> has paid for <strong>Request ' . $this->payment->additional_request_id . '</strong>, Click here link  <a class="link-primary" href="' . $url . '" >Request # ' . $this->payment->additional_request_id . '</a>',
                'url' => $url
            ];
        } elseif ($this->payment->gift_card_id != null) {
            $url = route("gift-card.edit", $this->payment->gift_card_id);
            return [
                'gift_card_id' => $this->payment->gift_card_id,
                'message' => 'Customer <strong>' . $customerDetailURL . '</strong> has paid for <strong>Gift Card ' . $this->payment->gift_card_id . '</strong>, Click here link  <a class="link-primary" href="' . $url . '" >Request # ' . $this->payment->gift_card_id . '</a>',
                'url' => $url
            ];
        }
    }
}
