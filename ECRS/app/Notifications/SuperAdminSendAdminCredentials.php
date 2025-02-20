<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuperAdminSendAdminCredentials extends Notification
{
    use Queueable;

    protected $plainPassword;
    protected $fname;
    protected $lname;
    protected $email;
    protected $salutation;


    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword, $fname, $lname, $salutation, $email)
    {
        $this->plainPassword = $plainPassword;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->salutation = $salutation;
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
                'plainPassword' => $this->plainPassword, 
                'fname' => $this->fname,
                'lname' => $this->lname,
                'email' => $this->email,
                'salutation' => $this->salutation,
                'url' => url('admin'),
            ]);
    }
}
