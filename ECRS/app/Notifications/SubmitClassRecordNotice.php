<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmitClassRecordNotice extends Notification
{
    use Queueable;

    public $type;
    public $course;
    public $professorId;
    public $classRecordID;


    public function __construct($type, $course, $professorId, $classRecordID)
    {
        $this->type = $type;
        $this->course = $course;
        $this->professorId = $professorId;
        $this->classRecordID = $classRecordID;

    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'data' => [
                'message'  => 'Kindly submit your <strong>' . $this->course . '</strong> class record at your earliest convenience.',
                'course'   => $this->course,
                'professorId'   => $this->professorId,
                // 'url' => route('notif.notice-submit', [
                //     'classRecordID' => $this->classRecordID,
                // ]),
                'classRecordID' => $this->classRecordID
            ],


        ];
    }
}
