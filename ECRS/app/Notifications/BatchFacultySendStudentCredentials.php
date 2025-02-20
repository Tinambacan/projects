<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchFacultySendStudentCredentials extends Notification
{
    use Queueable;

    protected $plainPassword;
    protected $studentNo;
    protected $studentFname;
    protected $studentLname;
    protected $studentMname;
    protected $studentEmail;



    /**
     * Create a new notification instance.
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
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Credentials')
            ->view('emails.student-account', [
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
