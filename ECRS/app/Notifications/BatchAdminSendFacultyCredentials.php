<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchAdminSendFacultyCredentials extends Notification
{
    use Queueable;

    protected $plainPassword;
    protected $profFname;
    protected $profLname;
    protected $profMname;
    protected $profSname;
    protected $profSalutation;
    protected $profEmail;



    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword, $profFname, $profLname, $profMname, $profSname, $profSalutation, $profEmail)
    {
        $this->plainPassword = $plainPassword;
        $this->profFname = $profFname;
        $this->profLname = $profLname;
        $this->profMname = $profMname;
        $this->profSname = $profSname;
        $this->profSalutation = $profSalutation;
        $this->profEmail = $profEmail;
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
            ->view('emails.faculty-account', [
                'plainPassword' => $this->plainPassword,  // Pass the plain password
                'fname' => $this->profFname,
                'lname' => $this->profLname,
                'email' => $this->profEmail,
                'salutation' => $this->profSalutation,
                'url' => url('faculty'),
            ]);
    }
}
