<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FacultySendSemesterGrades extends Notification
{
    use Queueable;

    protected $type;
    protected $professorSalutation;
    protected $professorLname;
    protected $professorFname;
    protected $courseTitle;
    protected $classRecordID;
    protected $fileID;


    /**
     * Create a new notification instance.
     */
    public function __construct($type, $professorSalutation, $professorLname, $professorFname, $courseTitle, $classRecordID, $fileID)
    {
        $this->type = $type;
        $this->professorSalutation = $professorSalutation;
        $this->professorLname = $professorLname;
        $this->professorFname = $professorFname;
        $this->courseTitle = $courseTitle;
        $this->classRecordID = $classRecordID;
        $this->fileID = $fileID;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        return [
            'type' => $this->type,
            'data' => [
                'message' => '<strong>' . $this->professorSalutation . ' ' . $this->professorFname . ' ' . $this->professorLname . '</strong> has submitted grades for <strong>' . $this->courseTitle . '</strong>.',
                'professor' => [
                    'firstName' => $this->professorFname,
                    'lastName' => $this->professorLname,
                ],
                'classRecordID' => $this->classRecordID,
                'fileID' => $this->fileID,
            ],
        ];
    }
}
