<?php

namespace App\Http\Controllers;

use App\Mail\FacultySendScoresToViewEmail;
use App\Models\Assessment;
use App\Models\AuditTrail;
use App\Models\ClassRecord;
use App\Models\Login;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\FacultySendGradesToView;
use App\Notifications\FacultySendGradesToViewEmail;
use App\Notifications\FacultySendSemesterGrades;
use App\Notifications\StudentRequestViewGrades;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{

    public function getNotifications()
    {
        $loginID = session('loginID');

        $role = session('role');

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notificationData = json_decode($notification->data, true);

                return [
                    'id' => $notification->id,
                    'type' => $notificationData['type'] ?? 'No type provided.',
                    'message' => $notificationData['data']['message'] ?? 'No message provided.',
                    'classRecordID' => $notificationData['data']['classRecordID'] ?? null,
                    'read_at' => $notification->read_at,
                    'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                ];
            });

        $unreadCount = $notifications->where('read_at', null)->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }




    public function streamNotifications()
    {
        $loginID = session('loginID');

        return response()->stream(function () use ($loginID) {
            while (true) {
                $notifications = DB::table('notifications')
                    ->where('notifiable_id', $loginID)
                    ->latest('created_at')
                    ->get()
                    ->map(function ($notification) {
                        $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                        $notificationData = json_decode($notification->data, true);
                        $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                        $notification->type = $notificationData['type'] ?? 'No type provided.';
                        $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                        return $notification;
                    });

                $unreadCount = $notifications->whereNull('read_at')->count();

                echo "data: " . json_encode([
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount,
                ]) . "\n\n";

                ob_flush();
                flush();

                sleep(5); // Avoid CPU overuse
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }


    public function sendNotificationScoresToView(Request $request)
    {
        $professorId = $request->input('professor_id');
        $type = 'grade_request';
        $loginID = session('loginID');
        $selectedClassRecordID = session('selectedClassRecordID');
        $professor = Registration::find($professorId);

        if (!$professor) {
            return response()->json(['error' => 'Professor not found.'], 404);
        }

        $classRecord = ClassRecord::find($selectedClassRecordID);
        $courseTitle = $classRecord ? $classRecord->course->courseTitle : 'Unknown Course';

        // Update the StudentAssessment
        StudentAssessment::where('classRecordID', $selectedClassRecordID)
            ->update(['isRequestedToView' => 1]);

        // Send the notification
        $professor->notify(new StudentRequestViewGrades($type, $loginID, $selectedClassRecordID, $courseTitle));

        return response()->json(['message' => 'Notification sent successfully.']);
    }


    public function sendNotificationViewableScoreBatch(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $selectedAssessIDs = $request->input('selectedAssessIDs');
        $gradingType = $request->input('gradingType');
        $gradingTerm = $request->input('gradingTerm');

        $formattedGradingType = strtolower(str_replace(' ', '-', $gradingType));
        $notFormattedGradingType = strtolower($gradingType);
        $tryGradingType = $gradingType;
        $type = 'publish_score';
        $loginID = session('loginID');

        $userFaculty = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userFaculty->registration->Lname . ', ' . $userFaculty->registration->Fname;

        if (is_null($classRecordID)) {
            return response()->json(['message' => 'Invalid class record ID.'], 400);
        }

        if (is_null($selectedAssessIDs) || !is_array($selectedAssessIDs)) {
            return response()->json(['message' => 'Invalid assessment IDs.'], 400);
        }

        $classRecord = ClassRecord::find($classRecordID);
        $courseTitle = $classRecord ? $classRecord->course->courseTitle : 'Unknown Course';

        $students = $classRecord->students()->get();
        $studentsWithNoScores = [];

        foreach ($students as $student) {
            foreach ($selectedAssessIDs as $assessID) {
                $studentAssessment = StudentAssessment::where('studentID', $student->studentID)
                    ->where('assessmentID', $assessID)
                    ->where('classRecordID', $classRecordID)
                    ->first();

                if (is_null($studentAssessment) || is_null($studentAssessment->score)) {
                    $studentsWithNoScores[] = [
                        'studentFname' => $student->studentFname,
                        'studentLname' => $student->studentLname,
                        'assessmentName' => Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment'
                    ];
                }
            }
        }

        if (!empty($studentsWithNoScores)) {
            return response()->json([
                'message' => 'Some students have no scores.',
                'invalidStudentAssessments' => $studentsWithNoScores,
            ], 400);
        }

        $validStudentIDs = $students->pluck('studentID')->toArray();

        StudentAssessment::whereIn('studentID', $validStudentIDs)
            ->where('classRecordID', $classRecordID)
            ->whereHas('assessment', function ($query) use ($gradingTerm) {
                $query->where('term', $gradingTerm);
            })
            ->whereIn('assessmentID', $selectedAssessIDs)
            ->update(['isRawScoreViewable' => 1]);

        $selectedAssessIDsString = implode(',', $selectedAssessIDs);

        $assessmentNames = []; // Array to store assessment names

        foreach ($students as $student) {
            $studentTable = Student::where('email', $student->email)
                ->where('classRecordID', $classRecordID)
                ->first();

            if ($studentTable) {
                $assessments = Assessment::whereIn('assessmentID', $selectedAssessIDs)->pluck('assessmentName');

                $assessmentNames[] = $assessments;

                $registration = Registration::where('schoolIDNo', $studentTable->studentNo)->first();

                if (!$registration) {
                    return response()->json(['status' => 'error', 'message' => 'Registration record not found']);
                }

                $loginID = $registration->loginID;

                $login = Login::find($loginID);

                if (!$login) {
                    return response()->json(['status' => 'error', 'message' => 'Login record not found']);
                }

                $studentFullName = trim($studentTable->studentFname . ' ' . ($studentTable->studentMname ? $studentTable->studentMname . ' ' : '') . $studentTable->studentLname);


                if ($assessments->count() > 1) {
                    $assessmentName = $assessments->slice(0, -1)->implode(', ') . ' and ' . $assessments->last();
                } else {
                    $assessmentName = $assessments->first();
                }

                // Notification::route('mail', $studentTable->email)
                //     ->notify(new FacultySendGradesToViewEmail(
                //         $type,
                //         $loginID,
                //         $courseTitle,
                //         $classRecordID,
                //         $studentFullName,
                //         $studentTable->studentID,
                //         $formattedGradingType,
                //         $notFormattedGradingType,
                //         $tryGradingType,
                //         $assessmentName,
                //         $selectedAssessIDsString,
                //         $gradingTerm
                //     ));

                // Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                //     $type,
                //     $loginID,
                //     $courseTitle,
                //     $classRecordID,
                //     $studentFullName,
                //     $studentTable->studentID,
                //     $formattedGradingType,
                //     $notFormattedGradingType,
                //     $tryGradingType,
                //     $assessmentName,
                //     $selectedAssessIDsString,
                //     $gradingTerm
                // ));

                Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                    $courseTitle,
                    $studentFullName,
                    $classRecordID,
                    $tryGradingType,
                    $selectedAssessIDsString,
                ));

                // SendViewableScoreNotification::dispatch(
                //     $type,
                //     $studentTable->email,
                //     $loginID,
                //     $courseTitle,
                //     $classRecordID,
                //     $studentFullName,
                //     $tryGradingType,
                //     $selectedAssessIDsString,
                // );


                $login->notify(new FacultySendGradesToView(
                    $type,
                    $loginID,
                    $courseTitle,
                    $classRecordID,
                    $studentFullName,
                    $student->studentID,
                    $formattedGradingType,
                    $notFormattedGradingType,
                    $tryGradingType,
                    $assessmentName,
                    $selectedAssessIDsString,
                    $gradingTerm
                ));
            } else {
                return response()->json(['status' => 'error', 'message' => 'Student not found']);
            }

            Assessment::whereIn('assessmentID', $selectedAssessIDs)
                ->where('classRecordID', $classRecordID)
                ->update(['isPublished' => 1]);
        }

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Publish',
            'table_name' => 'assessment_tbl',
            'description' => "Published assessment " . $assessmentName . " in " .  $courseTitle . " in " .  $tryGradingType,
            'action_time' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Batch scores published successfully.']);
    }


    public function sendNotificationViewableScore(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $selectedAssessIDs = $request->input('selectedAssessIDs');
        $gradingType = $request->input('gradingType');
        $gradingTerm = $request->input('gradingTerm');

        $formattedGradingType = strtolower(str_replace(' ', '-', $gradingType));
        $notFormattedGradingType = strtolower($gradingType);
        $tryGradingType = $gradingType;
        $type = 'publish_score';
        $loginID = session('loginID');

        $userFaculty = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userFaculty->registration->Lname . ', ' . $userFaculty->registration->Fname;

        $classRecord = ClassRecord::find($classRecordID);
        $courseTitle = $classRecord ? $classRecord->course->courseTitle : 'Unknown Course';

        $students = $classRecord->students()->get();
        $studentsWithNoScores = [];

        foreach ($students as $student) {
            foreach ($selectedAssessIDs as $assessID) {
                $studentAssessment = StudentAssessment::where('studentID', $student->studentID)
                    ->where('assessmentID', $assessID)
                    ->where('classRecordID', $classRecordID)
                    ->first();

                if (is_null($studentAssessment) || is_null($studentAssessment->score)) {
                    $studentsWithNoScores[] = [
                        'studentFname' => $student->studentFname,
                        'studentLname' => $student->studentLname,
                        'assessmentName' => Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment'
                    ];
                }
            }
        }

        if (!empty($studentsWithNoScores)) {
            return response()->json([
                'message' => 'Some students have no scores.',
                'invalidStudentAssessments' => $studentsWithNoScores,
            ], 400);
        }

        // $validStudentIDs = $students->pluck('studentID')->toArray();

        $selectedAssessIDsString = implode(',', $selectedAssessIDs);

        $assessmentNames = [];

        foreach ($students as $student) {
            // $studentLogin = Login::where('email', $student->email)->first();

            // $studentTable = Student::where('email', $student->email)->first();

            // $studentTable = Student::where('email', $student->email)
            //     ->where('classRecordID', $classRecordID)
            //     ->get();

            $studentTable = Student::where('email', $student->email)
                ->where('classRecordID', $classRecordID)
                ->first();

            if ($studentTable) {



                foreach ($selectedAssessIDs as $assessID) {
                    $studentAssessment = StudentAssessment::where('studentID', $student->studentID)
                        ->where('classRecordID', $classRecordID)
                        ->where('assessmentID', $assessID)
                        ->where('isRawScoreViewable', 0)
                        ->first();

                    if ($studentAssessment) {
                        $studentFullName = trim($student->studentFname . ' ' . ($student->studentMname ? $student->studentMname . ' ' : '') . $student->studentLname);
                        $assessmentName = Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment';

                        $assessmentNames[] = $assessmentName;

                        if ($studentTable) {
                            $registration = Registration::where('schoolIDNo', $studentTable->studentNo)->first();

                            if (!$registration) {
                                return response()->json(['status' => 'error', 'message' => 'Registration record not found']);
                            }

                            $loginID = $registration->loginID;

                            $login = Login::find($loginID);

                            if (!$login) {
                                return response()->json(['status' => 'error', 'message' => 'Login record not found']);
                            }

                            $studentFullName = trim($studentTable->studentFname . ' ' . ($studentTable->studentMname ? $studentTable->studentMname . ' ' : '') . $studentTable->studentLname);

                            $assessmentName = Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment';


                            Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                                $courseTitle,
                                $studentFullName,
                                $classRecordID,
                                $tryGradingType,
                                $selectedAssessIDsString,
                            ));

                            $login->notify(new FacultySendGradesToView(
                                $type,
                                $loginID,
                                $courseTitle,
                                $classRecordID,
                                $studentFullName,
                                $student->studentID,
                                $formattedGradingType,
                                $notFormattedGradingType,
                                $tryGradingType,
                                $assessmentName,
                                $selectedAssessIDsString,
                                $gradingTerm
                            ));
                        } else {
                            return response()->json(['status' => 'error', 'message' => 'Student not found']);
                        }


                        $studentAssessment->update(['isRawScoreViewable' => 1]);
                    }
                }

                Assessment::whereIn('assessmentID', $selectedAssessIDs)
                    ->where('classRecordID', $classRecordID)
                    ->update(['isPublished' => 1]);
            }
        }

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Publish',
            'table_name' => 'assessment_tbl',
            'description' => "Published assessment " . $assessmentName . " in " .  $courseTitle . " in " .  $tryGradingType,
            'action_time' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Published scores successfully.']);
    }



    public function sendNotificationViewableScoreDetailsIndividual(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $selectedAssessIDs = $request->input('selectedAssessIDs');
        $gradingType = $request->input('gradingType');
        $gradingTerm = $request->input('gradingTerm');
        $studentID = $request->input('studentID');

        $formattedGradingType = strtolower(str_replace(' ', '-', $gradingType));
        $notFormattedGradingType = strtolower($gradingType);
        $tryGradingType = $gradingType;
        $type = 'publish_score';
        $loginID = session('loginID');

        $classRecord = ClassRecord::find($classRecordID);
        if (!$classRecord) {
            return response()->json(['message' => 'Class record not found.'], 404);
        }

        $courseTitle = $classRecord->course->courseTitle ?? 'Unknown Course';

        if (!$studentID) {
            return response()->json(['message' => 'Student ID is required.'], 400);
        }

        if (is_null($selectedAssessIDs)) {
            return response()->json(['message' => 'Assessment IDs are required.'], 400);
        }

        if (is_string($selectedAssessIDs)) {
            $selectedAssessIDs = [$selectedAssessIDs]; // Convert single ID to array
        } elseif (!is_array($selectedAssessIDs)) {
            return response()->json(['message' => 'Invalid format for Assessment IDs.'], 400);
        }

        if (empty($selectedAssessIDs)) {
            return response()->json(['message' => 'No assessment IDs provided.'], 400);
        }

        // Check if the student has any missing scores
        $studentsWithNoScores = [];

        foreach ($selectedAssessIDs as $assessID) {
            $studentAssessment = StudentAssessment::where('studentID', $studentID)
                ->where('assessmentID', $assessID)
                ->where('classRecordID', $classRecordID)
                ->first();

            if (is_null($studentAssessment) || is_null($studentAssessment->score)) {
                $studentsWithNoScores[] = [
                    'studentFname' => $studentAssessment->student->studentFname ?? 'Unknown',
                    'studentLname' => $studentAssessment->student->studentLname ?? 'Unknown',
                    'assessmentName' => Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment'
                ];
            }
        }

        if (!empty($studentsWithNoScores)) {
            return response()->json([
                'message' => 'Some students have no scores.',
                'invalidStudentAssessments' => $studentsWithNoScores,
            ], 400);
        }

        StudentAssessment::where('studentID', $studentID)
            ->where('classRecordID', $classRecordID)
            ->whereIn('assessmentID', $selectedAssessIDs)
            ->update(['isRawScoreViewable' => 1]);

        $selectedAssessIDsString = implode(',', $selectedAssessIDs);

        $student = $classRecord->students()->find($studentID);

        // dd($student);
        if ($student) {

            $studentTable = Student::where('email', $student->email)
                ->where('classRecordID', $classRecordID)
                ->first();

            if ($studentTable) {
                $registration = Registration::where('schoolIDNo', $studentTable->studentNo)->first();

                if (!$registration) {
                    return response()->json(['status' => 'error', 'message' => 'Registration record not found']);
                }

                $loginID = $registration->loginID;

                $login = Login::find($loginID);

                if (!$login) {
                    return response()->json(['status' => 'error', 'message' => 'Login record not found']);
                }

                $studentFullName = trim($studentTable->studentFname . ' ' . ($studentTable->studentMname ? $studentTable->studentMname . ' ' : '') . $studentTable->studentLname);

                $assessmentName = Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment';

                // Notification::route('mail', $studentTable->email)
                //     ->notify(new FacultySendScoresToViewEmail(
                //         $type,
                //         $loginID,
                //         $courseTitle,
                //         $classRecordID,
                //         $studentFullName,
                //         $studentTable->studentID,
                //         $formattedGradingType,
                //         $notFormattedGradingType,
                //         $tryGradingType,
                //         $assessmentName,
                //         $selectedAssessIDsString,
                //         $gradingTerm
                //     ));

                Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                    $courseTitle,
                    $studentFullName,
                    $classRecordID,
                    $tryGradingType,
                    $selectedAssessIDsString,
                ));

                $login->notify(new FacultySendGradesToView(
                    $type,
                    $loginID,
                    $courseTitle,
                    $classRecordID,
                    $studentFullName,
                    $student->studentID,
                    $formattedGradingType,
                    $notFormattedGradingType,
                    $tryGradingType,
                    $assessmentName,
                    $selectedAssessIDsString,
                    $gradingTerm
                ));
            } else {
                return response()->json(['status' => 'error', 'message' => 'Student not found']);
            }
        }
        return response()->json(['message' => 'Scores published and notification sent to the selected student successfully.'], 200);
    }


    public function sendNotificationViewableScoreDetailsBatch(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $selectedAssessIDs = $request->input('selectedAssessIDs');
        $gradingType = $request->input('gradingType');
        $gradingTerm = $request->input('gradingTerm');
        $selectedStudentIDs = $request->input('selectedStudentIDs');

        $formattedGradingType = strtolower(str_replace(' ', '-', $gradingType));
        $notFormattedGradingType = strtolower($gradingType);
        $type = 'publish_score';
        $loginID = session('loginID');

        $classRecord = ClassRecord::find($classRecordID);
        if (!$classRecord) {
            return response()->json(['message' => 'Class record not found.'], 404);
        }

        $courseTitle = $classRecord->course->courseTitle ?? 'Unknown Course';

        if (empty($selectedStudentIDs) || !is_array($selectedStudentIDs)) {
            return response()->json(['message' => 'Selected student IDs are required.'], 400);
        }

        if (is_null($selectedAssessIDs) || !is_array($selectedAssessIDs)) {
            return response()->json(['message' => 'Assessment IDs are required.'], 400);
        }

        $studentsWithNoScores = [];

        foreach ($selectedStudentIDs as $studentID) {
            foreach ($selectedAssessIDs as $assessID) {
                $studentAssessment = StudentAssessment::where('studentID', $studentID)
                    ->where('assessmentID', $assessID)
                    ->where('classRecordID', $classRecordID)
                    ->first();

                if (is_null($studentAssessment) || is_null($studentAssessment->score)) {
                    $studentsWithNoScores[] = [
                        'studentFname' => $studentAssessment->student->studentFname ?? 'Unknown',
                        'studentLname' => $studentAssessment->student->studentLname ?? 'Unknown',
                        'assessmentName' => Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment',
                    ];
                }
            }
        }

        if (!empty($studentsWithNoScores)) {
            return response()->json([
                'message' => 'Some students have no scores.',
                'invalidStudentAssessments' => $studentsWithNoScores,
            ], 400);
        }

        $selectedAssessIDsString = implode(',', $selectedAssessIDs);

        foreach ($selectedStudentIDs as $studentID) {
            StudentAssessment::where('studentID', $studentID)
                ->where('classRecordID', $classRecordID)
                ->whereIn('assessmentID', $selectedAssessIDs)
                ->update(['isRawScoreViewable' => 1]);

            $student = $classRecord->students()->find($studentID);
            if ($student) {

                // $studentLogin = Login::where('email', $student->email)->first();

                // $studentTable = Student::where('email', $student->email)->first();

                // if ($studentTable) {
                //     $studentFullName = trim($student->studentFname . ' ' . ($student->studentMname ? $student->studentMname . ' ' : '') . $student->studentLname);
                //     $assessmentName = Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment';

                //     $studentTable->notify(new FacultySendGradesToView(
                //         $type,
                //         $loginID,
                //         $courseTitle,
                //         $classRecordID,
                //         $studentFullName,
                //         $student->studentID,
                //         $formattedGradingType,
                //         $notFormattedGradingType,
                //         $gradingType,
                //         $assessmentName,
                //         $selectedAssessIDsString,
                //         $gradingTerm
                //     ));
                // }

                // $studentTable = Student::where('email', $student->email)->first();

                $studentTable = Student::where('email', $student->email)
                    ->where('classRecordID', $classRecordID)
                    ->first();


                if ($studentTable) {
                    $registration = Registration::where('schoolIDNo', $studentTable->studentNo)->first();

                    if (!$registration) {
                        return response()->json(['status' => 'error', 'message' => 'Registration record not found']);
                    }

                    $loginID = $registration->loginID;

                    $login = Login::find($loginID);

                    if (!$login) {
                        return response()->json(['status' => 'error', 'message' => 'Login record not found']);
                    }

                    $studentFullName = trim($studentTable->studentFname . ' ' . ($studentTable->studentMname ? $studentTable->studentMname . ' ' : '') . $studentTable->studentLname);

                    $assessmentName = Assessment::find($assessID)->assessmentName ?? 'Unknown Assessment';

                    // Notification::route('mail', $studentTable->email)
                    //     ->notify(new FacultySendGradesToViewEmail(
                    //         $type,
                    //         $loginID,
                    //         $courseTitle,
                    //         $classRecordID,
                    //         $studentFullName,
                    //         $studentTable->studentID,
                    //         $formattedGradingType,
                    //         $notFormattedGradingType,
                    //         $gradingType,
                    //         $assessmentName,
                    //         $selectedAssessIDsString,
                    //         $gradingTerm
                    //     ));

                    // Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                    //     $type,
                    //     $loginID,
                    //     $courseTitle,
                    //     $classRecordID,
                    //     $studentFullName,
                    //     $studentTable->studentID,
                    //     $formattedGradingType,
                    //     $notFormattedGradingType,
                    //     $gradingType,
                    //     $assessmentName,
                    //     $selectedAssessIDsString,
                    //     $gradingTerm
                    // ));

                    Mail::to($studentTable->email)->send(new FacultySendScoresToViewEmail(
                        $courseTitle,
                        $studentFullName,
                        $classRecordID,
                        $gradingType,
                        $selectedAssessIDsString,
                    ));

                    $login->notify(new FacultySendGradesToView(
                        $type,
                        $loginID,
                        $courseTitle,
                        $classRecordID,
                        $studentFullName,
                        $student->studentID,
                        $formattedGradingType,
                        $notFormattedGradingType,
                        $gradingType,
                        $assessmentName,
                        $selectedAssessIDsString,
                        $gradingTerm
                    ));
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Student not found']);
                }

                // Assessment::whereIn('assessmentID', $selectedAssessIDs)
                //     ->where('classRecordID', $classRecordID)
                //     ->update(['isPublished' => 1]);
            }
        }
        return response()->json(['message' => 'Batch scores published and notifications sent successfully.'], 200);
    }

    public function markAsReadStoreClassRecordId(Request $request)
    {

        // Store the classRecordID in the session
        session(['selectedClassRecordID' => $request->input('classRecordIDRequest')]);

        // Get the notification ID from the request
        $notifID = $request->input('notifID');

        // Find the user
        $loginID = session('loginID');
        $user = Registration::with('notifications')->find($loginID);

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }

        // Find the notification
        $notification = $user->notifications->find($notifID);

        if ($notification) {
            // Update the read_at timestamp
            $notification->markAsRead();

            // Optionally, handle additional logic here
            $redirectUrl = route('faculty.view-class-record-stud-info'); // Adjust this based on your needs

            // Redirect to the specified URL
            return redirect($redirectUrl)->with('success', 'Notification marked as read.');
        } else {
            // Handle the case where the notification is not found
            return redirect()->back()->withErrors('Notification not found.');
        }
    }

    public function markAsReadNavigateToVerifiedFiles(Request $request)
    {
        $notifID = $request->input('notifIDVerified');
        $loginID = session('loginID');

        // Ensure loginID is present
        if (!$loginID) {
            return redirect()->back()->withErrors('User session not found.');
        }

        // dd($notifID);
        // Find the user and notifications
        // $user = Registration::with('notifications')->find($loginID);

        $user = Login::with('registration')->find($loginID);

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }


        $notification = $user->notifications->find($notifID);

        // dd($notification);

        if ($notification) {
            // Update the read_at timestamp
            $notification->markAsRead();

            // Redirect to the specified URL
            return redirect()->route('faculty.submitted-report');
        } else {
            // Handle the case where the notification is not found
            return redirect()->back()->withErrors('Notification not found.');
        }
    }

    public function markAllAsRead(Request $request)
    {
        $loginID = session('loginID');

        $user = Login::with('notifications')->find($loginID);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        // Mark all notifications as read
        foreach ($user->notifications as $notification) {
            if (!$notification->read_at) {
                $notification->markAsRead();
            }
        }

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }
}
