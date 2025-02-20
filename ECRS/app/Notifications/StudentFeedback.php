<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentFeedback extends Notification
{
    use Queueable;

    protected $type;
    protected $loginID;
    protected $courseCode;
    protected $classRecordID;
    protected $studentFullName;
    protected $studentID;
    protected $formattedGradingType;
    protected $notFormattedGradingType;
    protected $tryGradingType;
    protected $gradingTerm;
    protected $fname;
    protected $salutation;



    /**
     * Create a new notification instance.
     */
    public function __construct($type, $loginID, $courseCode, $classRecordID, $studentFullName, $studentID, $formattedGradingType, $notFormattedGradingType, $tryGradingType, $gradingTerm , $fname, $salutation)
    {
        $this->type = $type;
        $this->loginID = $loginID;
        $this->courseCode = $courseCode;
        $this->classRecordID = $classRecordID;
        $this->studentFullName = $studentFullName;
        $this->studentID = $studentID;
        $this->formattedGradingType = $formattedGradingType;
        $this->notFormattedGradingType = $notFormattedGradingType;
        $this->tryGradingType = $tryGradingType;
        $this->gradingTerm = $gradingTerm;
        $this->fname = $fname;
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
            ->subject('Student Feedback ' . '(' . $this->courseCode .')')
            ->view('emails.student-feedback', [
                'courseCode' => $this->courseCode,
                'fname' => $this->fname,
                'salutation' =>  $this->salutation,
                'url' => route('faculty.feedback'),
            ]);
    }
}
