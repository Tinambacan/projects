<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentForgotPass extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $studentNum;
    public $url;
    /**
     * Create a new message instance.
     */
    public function __construct($email,$studentNum)
    {
        //
        $this->email = $email;
        $this->studentNum = $studentNum;
        $this->url = route('student-change-pass', ['email' => $email ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Forgot Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.student-forgot-pass',
            with: [
                'url' => $this->url,
                'student_num' => $this->studentNum,

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
