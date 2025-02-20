<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacultyAccountCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $plainPassword;
    public $firstName;
    public $lastName;
    public $salutation;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($plainPassword, $firstName, $lastName, $salutation, $email)
    {
        $this->plainPassword = $plainPassword;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->salutation = $salutation;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.account-faculty')
            ->subject('Faculty Account Credentials')
            ->with([
                'plainPassword' => $this->plainPassword,
                'fname' => $this->firstName,
                'lname' => $this->lastName,
                'salutation' => $this->salutation,
                'email' => $this->email,
                'url' => url('faculty')
            ]);
    }
}
