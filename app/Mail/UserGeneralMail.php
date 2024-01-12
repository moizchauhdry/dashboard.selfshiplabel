<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserGeneralMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // if (isset($this->data['attachment'])) {
        //     return $this->subject($this->data['subject'])->view('mail.general-mail')
        //         ->attach('public/' . $this->data['attachment'], [
        //             'as' => 'orderPayment.pdf',
        //             'mime' => 'application/pdf',
        //         ]);
        // } else {
        return $this->subject($this->data['subject'])->view('mail.general-mail');
        // }
    }
}
