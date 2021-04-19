<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $actionUrl, $actionText, $introLines, $outroLines, $displayableActionUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($actionUrl, $actionText, $introLines, $outroLines, $displayableActionUrl)
    {
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->introLines = $introLines;
        $this->outroLines = $outroLines;
        $this->displayableActionUrl = $displayableActionUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgot');
    }
}
