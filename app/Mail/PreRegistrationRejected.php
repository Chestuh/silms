<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreRegistrationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $preRegistration;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($preRegistration, $reason)
    {
        $this->preRegistration = $preRegistration;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Application Was Rejected')
                    ->view('emails.pre_registration_rejected');
    }
}
