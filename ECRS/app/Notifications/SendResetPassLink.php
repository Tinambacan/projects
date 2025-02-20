<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendResetPassLink extends Notification
{
    use Queueable;

    protected $fname;
    protected $salutation;
    protected $resetLink;

    /**
     * Create a new notification instance.
     */
    public function __construct($fname, $salutation, $resetLink)
    {
        $this->fname = $fname; 
        $this->salutation = $salutation; 
        $this->resetLink = $resetLink;
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
            ->subject('Password Reset')
            ->view('emails.user-password-reset', [
                'salutation' => $this->salutation, 
                'fname' => $this->fname,
                'resetLink' => $this->resetLink,
            ]);
    }
}
