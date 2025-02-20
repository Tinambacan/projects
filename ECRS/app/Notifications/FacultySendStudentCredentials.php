<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FacultySendStudentCredentials extends Notification
{
    use Queueable;

    protected $plainPassword;
    protected $fname;
    protected $lname;
    protected $mname;
    protected $studentno;



    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword, $fname, $lname, $mname, $studentno)
    {
        $this->plainPassword = $plainPassword;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->studentno = $studentno;
        $this->mname = $mname;
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
            ->subject('Student Account Credentials')
            ->view('emails.student-account', [
                'plainPassword' => $this->plainPassword,  // Pass the plain password
                'fname' => $this->fname,
                'lname' => $this->lname,
                'studentno' => $this->studentno,
                'mname' => $this->mname,
                'url' => url('student'),
            ]);
    }
}
