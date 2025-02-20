<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BatchStudentAccountCredentials extends Mailable
{
    use Queueable, SerializesModels;

    protected $plainPassword;
    protected $studentNo;
    protected $studentFname;
    protected $studentLname;
    protected $studentMname;
    protected $studentEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($plainPassword, $studentNo, $studentFname, $studentLname, $studentMname, $studentEmail)
    {
        $this->plainPassword = $plainPassword;
        $this->studentNo = $studentNo;
        $this->studentFname = $studentFname;
        $this->studentLname = $studentLname;
        $this->studentMname = $studentMname;
        $this->studentEmail = $studentEmail;
    }
    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.account-student')
            ->subject('Account Credentials')
            ->with([
                'plainPassword' => $this->plainPassword,
                'studentno' => $this->studentNo,
                'fname' => $this->studentFname,
                'lname' => $this->studentLname,
                'mname' => $this->studentMname,
                'email' => $this->studentEmail,
                'url' => url('student'),
            ]);
    }
}
