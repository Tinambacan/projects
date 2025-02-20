<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotificationFacultyLoads extends Notification
{
    use Queueable;

    protected $type;
    protected $semester;
    protected $schoolYear;
    protected $course;

    /**
     * Create a new notification instance.
     *
     * @param string $type
     * @param string $semester
     * @param int $loginID
     */
    public function __construct($type, $semester, $schoolYear, $course)
    {
        $this->type = $type;
        $this->semester = $semester;
        $this->schoolYear = $schoolYear;
        $this->course = $course;
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
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'data' => [
                'message' => 'You have a new load <strong>' . $this->course . '</strong> for the <strong>' . $this->semester . '</strong> SY ' . $this->schoolYear . '. Please check your assigned courses and programs.',
                'url' => route('faculty.class-record'),
            ],
        ];
    }
}
