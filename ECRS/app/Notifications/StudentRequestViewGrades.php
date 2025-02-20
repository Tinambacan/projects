<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StudentRequestViewGrades extends Notification
{
    use Queueable;

    protected $type;
    protected $loginID;
    protected $selectedClassRecordID;
    protected $courseTitle;

    /**
     * Create a new notification instance.
     */
    public function __construct($type, $loginID, $selectedClassRecordID, $courseTitle)
    {
        $this->type = $type;
        $this->loginID = $loginID;
        $this->loginID = $loginID;
        $this->selectedClassRecordID = $selectedClassRecordID;
        $this->courseTitle = $courseTitle;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // Fetch the student's name
        $student = Registration::where('loginID', $this->loginID)->first();
        $studentName = $student ? $student->Fname . ' ' . $student->Lname : 'Unknown Student';

        return [
            'type' => $this->type,
            'data' => [
                'message' => "{$studentName} has requested to view grades in {$this->courseTitle}.",
                'action_url' => url('/professor/grade-requests'),
                'student_loginID' => $this->loginID,
                'classRecordID' => $this->selectedClassRecordID,
            ],
        ];
    }
}
