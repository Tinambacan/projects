<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminAccountCredentials extends Mailable
{
    use Queueable;

    protected $plainPassword;
    protected $fname;
    protected $lname;
    protected $email;
    protected $salutation;

    /**
     * Create a new message instance.
     */
    public function __construct($plainPassword, $fname, $lname, $salutation, $email)
    {
        $this->plainPassword = $plainPassword;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->salutation = $salutation;
    }


    public function build()
    {
        return $this->view('emails.account-admin')
            ->subject('Account Credentials')
            ->with([
                'plainPassword' => $this->plainPassword, 
                'fname' => $this->fname,
                'lname' => $this->lname,
                'email' => $this->email,
                'salutation' => $this->salutation,
                'url' => url('admin'),
            ]);
    }
}
