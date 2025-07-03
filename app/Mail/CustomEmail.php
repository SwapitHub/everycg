<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $messageBody;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact, $messageBody)
    {
        $this->contact = $contact;
        $this->messageBody = $messageBody;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.custom_email')
            ->subject("Hello " . $this->contact->name)
            ->with([
                'contact' => $this->contact,
                'body' => $this->messageBody,
            ]);
    }
}
