<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentAccountCredentials extends Mailable
{
    use Queueable;

    // protected $plainPassword;
    // protected $fname;
    // protected $lname;
    // protected $mname;
    // protected $studentno;
    // protected $email;

    protected $plainPassword;
    protected $studentNo;
    protected $studentFname;
    protected $studentLname;
    protected $studentMname;

    // Mail::to($login->email)->send(new StudentAccountCredentials(
    //     $generatedPassword,
    //     $request->studentFname,
    //     $request->studentLname,
    //     $request->studentMname,
    //     $request->studentNo
    // ));

    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword, $studentFname, $studentLname, $studentMname, $studentNo)
    {
        $this->plainPassword = $plainPassword;
        $this->studentFname = $studentFname;
        $this->studentLname = $studentLname;
        $this->studentMname = $studentMname;
        $this->studentNo = $studentNo;
    }

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
                'url' => url('student'),
            ]);
    }
}
