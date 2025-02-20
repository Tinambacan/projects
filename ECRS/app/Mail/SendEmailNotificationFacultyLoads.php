<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailNotificationFacultyLoads extends Mailable
{
    use Queueable;

    public $type;
    public $adminSalutation;
    public $adminLname;
    public $adminFname;


    public function __construct($type, $adminSalutation, $adminLname, $adminFname)
    {
        $this->type = $type;
        $this->adminSalutation = $adminSalutation;
        $this->adminLname = $adminLname;
        $this->adminFname = $adminFname;
    }

    public function build()
    {
        return $this->view('emails.faculty-loads-notif')
            ->subject('PUPT Faculty Schedules Updated')
            ->with([
                'type' => $this->type,
                'adminSalutation' => $this->adminSalutation,
                'adminLname' => $this->adminLname,
                'adminFname' => $this->adminFname,
                'url' => route('admin.faculty-loads-page'),
            ]);
    }
}
