<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreRegistrationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $preRegistration;

    /**
     * Create a new message instance.
     */
    public function __construct($preRegistration)
    {
        $this->preRegistration = $preRegistration;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Application Has Been Approved')
                    ->view('emails.pre_registration_approved');
    }
}
