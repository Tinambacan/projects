<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitClassRecordReportEmail extends Mailable
{
    use Queueable;

    public $type;
    public $professorSalutation;
    public $professorLname;
    public $professorFname;
    public $adminSalutation;
    public $adminLname;
    public $adminFname;
    public $courseTitle;



    public function __construct($type, $professorSalutation, $professorLname, $professorFname, $adminSalutation, $adminLname, $adminFname, $courseTitle)
    {
        $this->type = $type;
        $this->professorSalutation = $professorSalutation;
        $this->professorLname = $professorLname;
        $this->professorFname = $professorFname;
        $this->adminSalutation = $adminSalutation;
        $this->adminLname = $adminLname;
        $this->adminFname = $adminFname;
        $this->courseTitle = $courseTitle;
    }

    public function build()
    {
        return $this->view('emails.class-record-report')
            ->subject('Class Record Report Submitted')
            ->with([
                'type' => $this->type,
                'professorSalutation' => $this->professorSalutation,
                'professorLname' => $this->professorLname,
                'professorFname' => $this->professorFname,
                'adminSalutation' => $this->adminSalutation,
                'adminLname' => $this->adminLname,
                'adminFname' => $this->adminFname,
                'courseTitle' => $this->courseTitle,
                'url' => route('admin.class-record-report'),
            ]);
    }
}
