<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AdminValidateGradesFile extends Notification
{
    use Queueable;

    protected $type;
    protected $adminFirstName;
    protected $adminLastName;
    protected $classRecordID;
    protected $fileID;

    /**
     * Create a new notification instance.
     */
    public function __construct($type, $adminFirstName, $adminLastName, $classRecordID, $fileID)
    {
        $this->type = $type;
        $this->adminFirstName = $adminFirstName;
        $this->adminLastName = $adminLastName;
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
                'message' => 'Admin ' . $this->adminFirstName . ' ' . $this->adminLastName .
                    ' has verified your submitted file.',
                'url' => url('/student/class-record/midterm'),
                'admin' => [
                    'firstName' => $this->adminFirstName,
                    'lastName' => $this->adminLastName,
                ],
                'classRecordID' => $this->classRecordID,
                'fileID' => $this->fileID,
            ],
        ];
    }
}
