<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchSuperAdminSendAdminCredentials extends Notification
{
    use Queueable;

    protected $plainPassword;
    protected $adminFname;
    protected $adminLname;
    protected $adminMname;
    protected $adminSname;
    protected $adminSalutation;
    protected $adminEmail;



    /**
     * Create a new notification instance.
     */
    public function __construct($plainPassword, $adminFname, $adminLname, $adminMname, $adminSname, $adminSalutation, $adminEmail)
    {
        $this->plainPassword = $plainPassword;
        $this->adminFname = $adminFname;
        $this->adminLname = $adminLname;
        $this->adminMname = $adminMname;
        $this->adminSname = $adminSname;
        $this->adminSalutation = $adminSalutation;
        $this->adminEmail = $adminEmail;
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
                'fname' => $this->adminFname,
                'lname' => $this->adminLname,
                'email' => $this->adminEmail,
                'salutation' => $this->adminSalutation,
                'url' => url('faculty'),
            ]);
    }
}
