<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordResetForm extends Mailable
{
    use Queueable, SerializesModels;

    public $fname;
    public $salutation;
    public $resetLink;

    /**
     * Create a new message instance.
     */
    public function __construct($fname, $salutation, $resetLink)
    {
        $this->fname = $fname;
        $this->salutation = $salutation;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.password-reset')
            ->subject('Password Reset')
            ->with([
                'fname' => $this->fname,
                'salutation' => $this->salutation,
                'resetLink' => $this->resetLink,
            ]);
    }
}
