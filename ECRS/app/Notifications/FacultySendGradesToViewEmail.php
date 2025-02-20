<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FacultySendGradesToViewEmail extends Notification
{
    use Queueable;

    protected $type;
    protected $loginID;
    protected $courseTitle;
    protected $classRecordID;
    protected $studentFullName;
    protected $studentID;
    protected $formattedGradingType;
    protected $notFormattedGradingType;
    protected $tryGradingType;
    protected $assessmentName;
    protected $selectedAssessIDsString;
    protected $gradingTerm;

    /**
     * Create a new notification instance.
     */
    public function __construct($type, $loginID, $courseTitle, $classRecordID, $studentFullName, $studentID, $formattedGradingType, $notFormattedGradingType, $tryGradingType, $assessmentName, $selectedAssessIDsString, $gradingTerm)
    {
        $this->type = $type;
        $this->loginID = $loginID;
        $this->courseTitle = $courseTitle;
        $this->classRecordID = $classRecordID;
        $this->studentFullName = $studentFullName;
        $this->studentID = $studentID;
        $this->formattedGradingType = $formattedGradingType;
        $this->notFormattedGradingType = $notFormattedGradingType;
        $this->tryGradingType = $tryGradingType;
        $this->assessmentName = $assessmentName;
        $this->selectedAssessIDsString = $selectedAssessIDsString;
        $this->gradingTerm = $gradingTerm;
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
            ->subject('Your score is ready view for ' . $this->courseTitle)
            ->view('emails.student-grades', [
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
