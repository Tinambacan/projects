<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FacultySendScoresToViewEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $courseTitle;
    public $studentFullName;
    public $classRecordID;
    public $tryGradingType;
    public $selectedAssessIDsString;

    /**
     * Create a new message instance.
     */
    public function __construct($courseTitle, $studentFullName, $classRecordID, $tryGradingType, $selectedAssessIDsString)
    {
        $this->courseTitle = $courseTitle;
        $this->studentFullName = $studentFullName;
        $this->classRecordID = $classRecordID;
        $this->tryGradingType = $tryGradingType;
        $this->selectedAssessIDsString = $selectedAssessIDsString;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your score is ready to view for ' . $this->courseTitle)
            ->view('emails.scores-student', [
                'courseTitle' => $this->courseTitle,
                'url' => route('student.store-class-record-id-email', [
                    'classRecordID' => $this->classRecordID,
                    'GradingType' => $this->tryGradingType,
                    'selectedAssessIDs' => $this->selectedAssessIDsString,
                ]),
                'studentFullName' => $this->studentFullName,
            ]);
    }
}
