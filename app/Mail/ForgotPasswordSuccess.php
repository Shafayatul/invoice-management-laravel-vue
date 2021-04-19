<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordSuccess extends Mailable
{
    use Queueable, SerializesModels;
    public $lines;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($lines)
    {
        $this->lines = $lines;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgot_password');
    }
}
