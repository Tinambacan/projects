<?php

namespace App\Jobs;

use App\Mail\FacultySendGradesToViewEmail;
use App\Models\Login;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendViewableScoreNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $type;
    protected string $email;
    protected int $loginID;
    protected string $courseTitle;
    protected int $classRecordID;
    protected string $studentFullName;
    protected int $studentID;
    protected string $formattedGradingType;
    protected string $notFormattedGradingType;
    protected string $tryGradingType;
    protected string $assessmentName;
    protected string $selectedAssessIDsString;
    protected string $gradingTerm;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $email,
        string $courseTitle,
        string $studentFullName,
        int $classRecordID,
        string $tryGradingType,
        string $selectedAssessIDsString,
    ) {
        $this->email = $email;
        $this->courseTitle = $courseTitle;
        $this->studentFullName = $studentFullName;
        $this->classRecordID = $classRecordID;
        $this->tryGradingType = $tryGradingType;
        $this->selectedAssessIDsString = $selectedAssessIDsString;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->email)->send(new FacultySendGradesToViewEmail(
            $this->courseTitle,
            $this->classRecordID,
            $this->tryGradingType,
            $this->studentFullName,
            $this->selectedAssessIDsString,
        ));
    }
}
