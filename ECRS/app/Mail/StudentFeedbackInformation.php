<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentFeedbackInformation extends Mailable
{
    use Queueable;

    protected $type;
    protected $loginID;
    protected $courseCode;
    protected $classRecordID;
    protected $studentID;
    protected $fname;
    protected $salutation;

    /**
     * Create a new message instance.
     */
    public function __construct($type, $loginID, $courseCode, $classRecordID, $studentID, $fname, $salutation)
    {
        $this->type = $type;
        $this->loginID = $loginID;
        $this->courseCode = $courseCode;
        $this->classRecordID = $classRecordID;
        $this->studentID = $studentID;
        $this->fname = $fname;
        $this->salutation = $salutation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.feedback-student')
            ->subject('Feedback Notification')
            ->with([
                'type' => $this->type,
                'loginID' => $this->loginID,
                'courseCode' => $this->courseCode,
                'classRecordID' => $this->classRecordID,
                'studentID' => $this->studentID,
                'fname' => $this->fname,
                'salutation' => $this->salutation,
                'url' => route('faculty.feedback'),
            ]);
    }
}

