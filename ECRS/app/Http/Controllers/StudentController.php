<?php

namespace App\Http\Controllers;

use App\Models\ClassRecord;
use App\Models\Feedback;
use App\Models\Grading;
use App\Models\GradingDistribution;
use App\Models\Login;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Admin;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exports\StudentAssessmentsExport;
use App\Mail\StudentFeedbackInformation;
use App\Models\Assessment;
use App\Models\AuditTrail;
use App\Notifications\StudentFeedback;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{

    // $user = Login::with('registration')->find($loginID);
    // $userinfo = $user ? $user->registration : null;

    public function studentDashboard()
    {
        $loginID = session('loginID');
        $role = session('role');
        $studentNo = session('studentNo');

        // Fetch student details
        $student = Student::where('studentNo', $studentNo)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Get classRecordID for the student
        $classRecordID = $student->classRecordID;

        // Fetch the user details
        $user = Registration::with(['login', 'student'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3); // Role 3 for students
            })
            ->where('loginID', $loginID)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $userinfo = $user;

        // Get school year & semester
        $registration = Registration::where('loginID', $loginID)->first();
        $schoolYear = null;
        $semester = null;

        if ($registration) {
            $admin = Admin::where('branch', $registration->branch)->first();
            if ($admin) {
                $schoolYear = $admin->schoolYear;
                $semester = $admin->semester;
            }
        }

        // Fetch the active class record
        $classRecord = ClassRecord::where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->orderByRaw("CASE 
            WHEN schoolYear = ? AND semester = ? THEN 0
            ELSE 1 
        END", [$schoolYear, $semester])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$classRecord) {
            return redirect()->back()->with('error', 'Class record not found');
        }

        // Fetch grading distribution for the class record
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get()->groupBy('term');

        // Compute semester grades for the student
        $classRecordIDs = DB::table('student_tbl')
        ->where('studentNo', $studentNo)
        ->pluck('classRecordID');
            
        $classRecords = ClassRecord::whereIn('classRecordID', $classRecordIDs)
        ->where('isArchived', 0)
        ->with(['course'])
        ->orderBy('created_at', 'desc')
        ->get();


        $studentGrades = [];
        $studentClassRecords = [];

        foreach ($classRecords as $classRecord) {
            $classRecordID = $classRecord->classRecordID;

            // Fetch the corresponding studentID for the class record
            $studentID = DB::table('student_tbl')
                ->where('classRecordID', $classRecordID)
                ->where('studentNo', $studentNo)
                ->pluck('studentID')
                ->first();

            // Store the studentID for the class record
            $studentClassRecords[$classRecordID] = $studentID;

            $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get()->groupBy('term');

            $grades = DB::table('student_assessment_tbl AS sa')
                ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
                ->join('grading_tbl AS g', function ($join) use ($classRecordID) {
                    $join->on('a.assessmentType', '=', 'g.assessmentType')
                        ->on('a.term', '=', 'g.term')
                        ->where('g.classRecordID', '=', $classRecordID);
                })
                ->join('grading_distribution_tbl AS gd', function ($join) {
                    $join->on('gd.classRecordID', '=', 'g.classRecordID')
                        ->on('gd.term', '=', 'g.term');
                })
                ->select(
                    'sa.studentID',
                    'a.term',
                    DB::raw('SUM(sa.score) / SUM(a.totalItem) * 100 AS rawGrade'), // Retrieve raw grade
                    'gd.isPublished'  // Select the isPublished field
                )
                ->where('sa.classRecordID', $classRecordID) // Ensure we get assessments for the specific class record
                ->groupBy('sa.studentID', 'a.term', 'gd.isPublished')
                ->get()
                ->groupBy('studentID');

            foreach ($grades as $gradeStudentID => $studentGrade) {
                if ($gradeStudentID != $studentID) {
                    continue; // Skip grades that do not match the relevant studentID
                }

                $termGrades = [];
                $semestralGrade = 0;
                $isIncomplete = false;

                // Get the terms that exist in the class record
                $existingTerms = $gradingDistributions->keys();

                foreach ($existingTerms as $term) {
                    $termAssessments = $studentGrade->where('term', $term);
                    $termGrade = $termAssessments->sum('rawGrade');
                    $termPercentage = $gradingDistributions[$term][0]->gradingDistributionPercentage ?? 0;
                    $isPublished = $termAssessments->first()->isPublished ?? null;

                    if ($termAssessments->isEmpty() || is_null($isPublished) || $isPublished == 0) {  // Check if term assessments are empty or not published
                        $termGrades["term{$term}Grade"] = 'Not set';
                        $isIncomplete = true;
                    } else {
                        $adjustedGrade = $termGrade * ($termPercentage / 100); // Apply percentage
                        $termGrades["term{$term}Grade"] = number_format($adjustedGrade, 2);
                        $semestralGrade += $adjustedGrade;
                    }
                }

                if ($isIncomplete) {
                    $termGrades['semestralGrade'] = 'Not set';
                    $termGrades['pointGrade'] = 'Not set';
                    $termGrades['gwa'] = 'Not set';
                    $termGrades['remarks'] = 'Not set';
                } else {
                    $termGrades['semestralGrade'] = number_format($semestralGrade, 2);
                    $pointGrade = $this->convertToPointGrade($semestralGrade);
                    list($gwa, $remarks) = $this->convertToGWAAndRemarks($pointGrade);

                    $termGrades['pointGrade'] = $pointGrade;
                    $termGrades['gwa'] = $gwa;
                    $termGrades['remarks'] = $remarks;
                }

                if (!isset($studentGrades[$classRecordID])) {
                    $studentGrades[$classRecordID] = [];
                }
                $studentGrades[$classRecordID][$gradeStudentID] = $termGrades; // Ensure unique entries per classRecordID and studentID
            }
        }

        // dd($studentGrades);  

        // Fetch notifications
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('student.student-dashboard', [
            'loginID' => $loginID,
            'role' => $role,
            'student' => $student, // Use singular variable name
            'user' => $user,
            'userinfo' => $userinfo,
            'classRecords' => $classRecords, // Pass as a collection
            'studentGrades' => $studentGrades,
            'studentClassRecords' => $studentClassRecords, // Pass the studentClassRecords
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
        
    }





    // Function to convert semestral grade to point grade
    private function convertToPointGrade($semestralGrade)
    {
        if ($semestralGrade == 100) {
            return 1.00;
        } elseif ($semestralGrade >= 99.875 && $semestralGrade <= 99.999) {
            return 1.01;
        } elseif ($semestralGrade >= 99.750 && $semestralGrade <= 99.874) {
            return 1.02;
        } elseif ($semestralGrade >= 99.625 && $semestralGrade <= 99.749) {
            return 1.03;
        } elseif ($semestralGrade >= 99.500 && $semestralGrade <= 99.624) {
            return 1.04;
        } elseif ($semestralGrade >= 99.125 && $semestralGrade <= 99.499) {
            return 1.07;
        } elseif ($semestralGrade >= 99.000 && $semestralGrade <= 99.124) {
            return 1.08;
        } elseif ($semestralGrade >= 98.875 && $semestralGrade <= 98.999) {
            return 1.09;
        } elseif ($semestralGrade >= 98.750 && $semestralGrade <= 98.874) {
            return 1.10;
        } elseif ($semestralGrade >= 98.625 && $semestralGrade <= 98.749) {
            return 1.11;
        } elseif ($semestralGrade >= 98.500 && $semestralGrade <= 98.624) {
            return 1.12;
        } elseif ($semestralGrade >= 98.375 && $semestralGrade <= 98.499) {
            return 1.13;
        } elseif ($semestralGrade >= 98.250 && $semestralGrade <= 98.374) {
            return 1.14;
        } elseif ($semestralGrade >= 98.125 && $semestralGrade <= 98.249) {
            return 1.15;
        } elseif ($semestralGrade >= 98.000 && $semestralGrade <= 98.124) {
            return 1.16;
        } elseif ($semestralGrade >= 97.000 && $semestralGrade <= 97.999) {
            return 1.124;
        } elseif ($semestralGrade >= 96.000 && $semestralGrade <= 96.999) {
            return 1.132;
        } elseif ($semestralGrade >= 95.000 && $semestralGrade <= 95.999) {
            return 1.140;
        } elseif ($semestralGrade >= 94.000 && $semestralGrade <= 94.999) {
            return 1.148;
        } elseif ($semestralGrade >= 93.000 && $semestralGrade <= 93.999) {
            return 1.156;
        } elseif ($semestralGrade >= 92.000 && $semestralGrade <= 92.999) {
            return 1.164;
        } elseif ($semestralGrade >= 91.000 && $semestralGrade <= 91.999) {
            return 1.172;
        } elseif ($semestralGrade >= 90.000 && $semestralGrade <= 90.999) {
            return 1.18;
        } elseif ($semestralGrade >= 89.000 && $semestralGrade <= 89.999) {
            return 1.88;
        } elseif ($semestralGrade >= 88.000 && $semestralGrade <= 88.999) {
            return 1.96;
        } elseif ($semestralGrade >= 87.000 && $semestralGrade <= 87.999) {
            return 2.04;
        } elseif ($semestralGrade >= 86.000 && $semestralGrade <= 86.999) {
            return 2.12;
        } elseif ($semestralGrade >= 85.000 && $semestralGrade <= 85.999) {
            return 2.20;
        } elseif ($semestralGrade >= 84.000 && $semestralGrade <= 84.999) {
            return 2.28;
        } elseif ($semestralGrade >= 83.000 && $semestralGrade <= 83.999) {
            return 2.36;
        } elseif ($semestralGrade >= 82.000 && $semestralGrade <= 82.999) {
            return 2.44;
        } elseif ($semestralGrade >= 81.000 && $semestralGrade <= 81.999) {
            return 2.52;
        } elseif ($semestralGrade >= 80.000 && $semestralGrade <= 80.999) {
            return 2.60;
        } elseif ($semestralGrade >= 79.000 && $semestralGrade <= 79.999) {
            return 2.68;
        } elseif ($semestralGrade >= 78.000 && $semestralGrade <= 78.999) {
            return 2.76;
        } elseif ($semestralGrade >= 77.000 && $semestralGrade <= 77.999) {
            return 2.84;
        } elseif ($semestralGrade >= 76.000 && $semestralGrade <= 76.999) {
            return 2.92;
        } elseif ($semestralGrade >= 75.000 && $semestralGrade <= 75.999) {
            return 3.00;
        } elseif ($semestralGrade >= 74.000 && $semestralGrade <= 74.999) {
            return 3.08;
        } elseif ($semestralGrade >= 73.000 && $semestralGrade <= 73.999) {
            return 3.16;
        } elseif ($semestralGrade >= 72.000 && $semestralGrade <= 72.999) {
            return 3.24;
        } elseif ($semestralGrade >= 71.000 && $semestralGrade <= 71.999) {
            return 3.32;
        } elseif ($semestralGrade >= 70.000 && $semestralGrade <= 70.999) {
            return 3.40;
        } else {
            return 5.00;
        }
    }
    private function convertToGWAAndRemarks($pointGrade)
    {
        if ($pointGrade >= 1.000 && $pointGrade <= 1.125) {
            return ["1.00", "Passed"];
        } elseif ($pointGrade >= 1.126 && $pointGrade <= 1.375) {
            return ["1.25", "Passed"];
        } elseif ($pointGrade >= 1.376 && $pointGrade <= 1.625) {
            return ["1.50", "Passed"];
        } elseif ($pointGrade >= 1.626 && $pointGrade <= 1.875) {
            return ["1.75", "Passed"];
        } elseif ($pointGrade >= 1.876 && $pointGrade <= 2.125) {
            return ["2.00", "Passed"];
        } elseif ($pointGrade >= 2.126 && $pointGrade <= 2.375) {
            return ["2.25", "Passed"];
        } elseif ($pointGrade >= 2.376 && $pointGrade <= 2.625) {
            return ["2.50", "Passed"];
        } elseif ($pointGrade >= 2.626 && $pointGrade <= 2.875) {
            return ["2.75", "Passed"];
        } elseif ($pointGrade >= 2.876 && $pointGrade <= 3.125) {
            return ["3.00", "Passed"];
        } elseif ($pointGrade >= 3.126 && $pointGrade <= 5.00) {
            return ["5.00", "Failed"];
        } else {
            return ["Incomplete", "Incomplete"];
        }
    }

    // $user = Login::with('registration')->find($loginID);
    // $userinfo = $user ? $user->registration : null;


    public function studentClassRecordPageInfo($gradingDistributionType)
    {
        $loginID = session('loginID');
        $role = session('role');
        $classRecordID = session('selectedClassRecordID');
        $gradingTerm = session('gradingTerm');
        $selectedTab = session('selectedTab');
        $selectedgradingDistributionType = session('selectedgradingDistributionType');

        // dd($gradingTerm);   



        // $selectedgradingDistributionType = session('selectedgradingDistributionType');

        // dd($selectedTab);

        // dd($gradingTerm);


        $selectedClassRecord = ClassRecord::with(['course.program', 'login.registration'])
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$selectedClassRecord) {
            return redirect()->back()->with('error', 'Class record not found');
        }

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;


        // $students = Student::where('classRecordID', $classRecordID)
        //     ->where('email', $user->login->email)
        //     ->first();

        $students = Student::where('classRecordID', $classRecordID)
            ->where('studentNo', $user->schoolIDNo)
            ->first();

        if (!$students) {
            return redirect()->back()->with('error', 'Student not found');
        }

        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();

        $selectedfirstGradingDistributionType = str_replace('-', ' ', $selectedTab);


        // dd($selectedfirstGradingDistributionType);

        // $selectedGradingDistributions = GradingDistribution::where('gradingDistributionType', $selectedfirstGradingDistributionType)->get();

        // dd( $selectedGradingDistributions);


        // $gradingPercentages = DB::table('grading_tbl')
        //     ->where('classRecordID', $classRecordID)
        //     ->where('term', $gradingTerm)
        //     ->get()
        //     ->pluck('percentage', 'assessmentType');

        $gradingPercentages = DB::table('grading_tbl')
            ->where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm)
            ->selectRaw('LOWER(assessmentType) as assessmentType, percentage')
            ->get()
            ->pluck('percentage', 'assessmentType');


        $studentAssessments = StudentAssessment::with(['assessment'])
            ->where('studentID', $students->studentID)
            ->where('classRecordID', $classRecordID)
            ->where('isRawScoreViewable', 1)
            ->whereHas('assessment', function ($query) use ($gradingTerm) {
                $query->where('term', $gradingTerm);
            })
            ->get();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();


        return view('student.student-class-record-info', [
            'loginID' => $loginID,
            'role' => $role,
            'students' => $students,
            'user' => $user,
            'userinfo' => $userinfo,
            'selectedClassRecord' => $selectedClassRecord,
            'classRecordOwner' => $selectedClassRecord->login->registration ?? null,
            'studentAssessments' => $studentAssessments,
            'gradingDistributions' => $gradingDistributions,
            'selectedGradingDistributions' => $selectedfirstGradingDistributionType,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'classRecordID' => $classRecordID,
            'gradingPercentages' => $gradingPercentages,
            'gradingDistributionType' => $gradingDistributionType,
            'selectedgradingDistributionType' => $selectedgradingDistributionType,
            'selectedTab' => $selectedTab
        ]);
    }


    public function studentClassRecordPageAssessmentDetails()
    {
        $loginID = session('loginID');
        $role = session('role');
        $classRecordID = session('selectedClassRecordID');
        $gradingTerm = session('gradingTerm');


        $selectedgradingDistributionType = session('selectedgradingDistributionType');
        $selectedAssessmentID = session('selectedAssessmentID');
        // dd($selectedAssessmentID);

        // dd($selectedgradingDistributionType);

        // $selectedTab = session('selectedTab');

        $selectedAssessmentIDs = is_array($selectedAssessmentID) ? $selectedAssessmentID : explode(',', $selectedAssessmentID);


        $formattedSelectedGradingDistributionType = strtolower(str_replace('-', ' ', $selectedgradingDistributionType));


        $selectedClassRecord = ClassRecord::with(['course.program', 'login.registration'])
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$selectedClassRecord) {
            return redirect()->back()->with('error', 'Class record not found');
        }

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // $students = Student::where('classRecordID', $classRecordID)
        //     ->where('email', $user->login->email)
        //     ->first();

        $students = Student::where('classRecordID', $classRecordID)
            ->where('studentNo', $user->schoolIDNo)
            ->first();

        // dd($students);

        if (!$students) {
            return redirect()->back()->with('error', 'Student not found');
        }

        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();

        $gradingPercentages = DB::table('grading_tbl')
            ->where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm)
            ->selectRaw('LOWER(assessmentType) as assessmentType, percentage')
            ->get()
            ->pluck('percentage', 'assessmentType');


        $studentAssessments = StudentAssessment::with(['assessment'])
            ->where('studentID', $students->studentID)
            ->where('classRecordID', $classRecordID)
            ->where('isRawScoreViewable', 1)
            ->whereHas('assessment', function ($query) use ($gradingTerm, $selectedAssessmentIDs) {
                $query->where('term', $gradingTerm)
                    ->whereIn('assessmentID', $selectedAssessmentIDs);
            })
            ->get();


        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();


        return view('student.student-assess-score-details', [
            'loginID' => $loginID,
            'role' => $role,
            'students' => $students,
            'user' => $user,
            'userinfo' => $userinfo,
            'selectedClassRecord' => $selectedClassRecord,
            'classRecordOwner' => $selectedClassRecord->login->registration ?? null,
            'studentAssessments' => $studentAssessments,
            'gradingDistributions' => $gradingDistributions,
            'selectedgradingDistributionType' => $formattedSelectedGradingDistributionType,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'classRecordID' => $classRecordID,
            'gradingPercentages' => $gradingPercentages,
            'selectedAssessmentID' => $selectedAssessmentID
        ]);
    }


    public function storeAssessmentIDStudent(Request $request)
    {
        $request->validate([
            'assessmentID' => 'required|integer',
        ]);

        $assessmentID = $request->input('assessmentID');

        $gradingDistributionType = $request->input('gradingDistributionType');

        $gradingDistributionType = strtolower($gradingDistributionType);

        // dd( $gradingDistributionType);


        session(['selectedAssessmentID' => $assessmentID]);

        $assessment = Assessment::find($assessmentID);

        if (!$assessment) {
            abort(404, 'Assessment not found.');
        }

        $gradingDistributionType = strtolower(str_replace(' ', '-', $gradingDistributionType));

        session(['selectedgradingDistributionType' => $gradingDistributionType]);

        session(['selectedTab' => $gradingDistributionType]);

        $redirectUrl = route('student.class-record-assessment-details', [
            'gradingDistributionType' => $gradingDistributionType
        ]);

        return redirect($redirectUrl);
    }

    public function redirectToLists(Request $request)
    {

        $gradingDistributionType = $request->input('gradingDistributionType');

        $gradingDistributionType = strtolower(str_replace(' ', '-', $gradingDistributionType));

        // dd($gradingDistributionType);

        $redirectUrl = route('student.class-record-info', ['gradingDistributionType' => $gradingDistributionType]);

        return redirect($redirectUrl);
    }

    public function exportStudentAssessments()
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID');
        $gradingTerm = session('gradingTerm');

        // Fetch user registration details using loginID
        $user = Registration::where('loginID', $loginID)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Fetch student using the registration relationship
        $student = Student::where('studentNo', $user->schoolIDNo)
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Fetch student assessments
        $studentAssessments = StudentAssessment::with(['assessment'])
            ->where('studentID', $student->studentID)
            ->where('classRecordID', $classRecordID)
            ->where('isRawScoreViewable', 1)
            ->whereHas('assessment', function ($query) use ($gradingTerm) {
                $query->where('term', $gradingTerm);
            })
            ->get();

        // Fetch grading percentages from grading_tbl
        // $gradingPercentages = DB::table('grading_tbl')
        //     ->where('classRecordID', $classRecordID)
        //     ->where('term', $gradingTerm)
        //     ->get()
        //     ->pluck('percentage', 'assessmentType');

        $gradingPercentages = DB::table('grading_tbl')
            ->where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm)
            ->selectRaw('LOWER(assessmentType) as assessmentType, percentage')
            ->get()
            ->pluck('percentage', 'assessmentType');

        // Fetch class record information for student info sheet
        $classRecord = ClassRecord::with(['program', 'course'])
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$classRecord) {
            return redirect()->back()->with('error', 'Class Record not found');
        }

        // Prepare student information
        $studentInfo = [
            ['Information' => 'Student Name', 'Fields' => "{$student->studentLname}, {$student->studentFname}"],
            ['Information' => 'Student No.', 'Fields' => $student->studentNo],
            ['Information' => 'Program', 'Fields' => $classRecord->program->programCode ?? 'N/A'],
            ['Information' => 'Course', 'Fields' => $classRecord->course->courseCode ?? 'N/A'],
            ['Information' => 'Year Level', 'Fields' => $classRecord->yearLevel ?? 'N/A'],
            ['Information' => 'Semester', 'Fields' => $this->getSemester($classRecord->semester)],
        ];


        $fileName = sprintf(
            '%s_%s_%s_%s_assessments.xlsx',
            $student->studentLname,
            $student->studentFname,
            $classRecord->program->programCode ?? 'N/A',
            $classRecord->course->courseCode ?? 'N/A'
        );

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $user->schoolIDNo,
            'action' => "Download",
            'table_name' => 'student_tbl',
            'new_value' => '',
            'description' => "Download scores",
            'action_time' => Carbon::now(),
        ]);

        return Excel::download(new StudentAssessmentsExport($studentAssessments, $gradingPercentages, $studentInfo), $fileName);
    }

    private function getSemester($semester)
    {
        switch ($semester) {
            case 1:
                return '1st Semester';
            case 2:
                return '2nd Semester';
            case 3:
                return 'Summer Semester';
            default:
                return 'N/A'; // or any other default value you prefer
        }
    }

    public function studentClassRecordPageMidterm()
    {
        $loginID = session('loginID');
        $role = session('role');
        $classRecordID = session('selectedClassRecordID');

        $selectedClassRecord = ClassRecord::with(['course.program', 'login.registration'])
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$selectedClassRecord) {
            return redirect()->back()->with('error', 'Class record not found');
        }

        // $user = Login::with('registration')->find($loginID);
        // $userinfo = $user ? $user->registration : null;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // $user = Registration::with(['login'])
        //     ->whereHas('login', function ($query) {
        //         $query->where('role', 3);
        //     })
        //     ->where('loginID', $loginID)
        //     ->first();

        // $userinfo = $user;


        $students = Student::where('classRecordID', $classRecordID)
            ->where('email', $user->login->email)
            ->first();

        if (!$students) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Use the correct `studentID` value if it's not null
        $studentAssessments = StudentAssessment::with('assessment')
            ->where('classRecordID', $classRecordID)
            ->where('studentID', $students->studentID)
            ->where('isRawScoreViewable', 1)
            ->where('isRequestedToView', 1)
            ->whereHas('assessment', function ($query) {
                $query->where('term', 1);
            })
            ->get();

        // $notifications = $user->notifications;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();


        return view('student.student-class-record-midterm', [
            'loginID' => $loginID,
            'role' => $role,
            'students' => $students,
            'user' => $user,
            'userinfo' => $userinfo,
            'selectedClassRecord' => $selectedClassRecord,
            'classRecordOwner' => $selectedClassRecord->login->registration ?? null,
            'studentAssessments' => $studentAssessments,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function studentClassRecordPageFinals()
    {
        $loginID = session('loginID');
        $role = session('role');
        $classRecordID = session('selectedClassRecordID');

        $selectedClassRecord = ClassRecord::with(['course.program', 'login.registration'])
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$selectedClassRecord) {
            return redirect()->back()->with('error', 'Class record not found');
        }

        // $user = Login::with('registration')->find($loginID);
        // $userinfo = $user ? $user->registration : null;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $students = Student::where('classRecordID', $classRecordID)
            ->where('email', $user->login->email)
            ->first();

        $studentAssessments = StudentAssessment::with('assessment')
            ->where('classRecordID', $classRecordID)
            ->where('studentID', $students->studentID)
            ->whereHas('assessment', function ($query) {
                $query->where('term', 2);
            })
            ->get();

        // $notifications = $user->notifications;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('student.student-class-record-finals', [
            'loginID' => $loginID,
            'role' => $role,
            'students' => $students,
            'user' => $user,
            'userinfo' => $userinfo,
            'selectedClassRecord' => $selectedClassRecord,
            'classRecordOwner' => $selectedClassRecord->login->registration ?? null,
            'studentAssessments' => $studentAssessments, // Pass the assessments to the view
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function storeStudentClassRecordId(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'classRecordIDView' => 'required|integer',
        ]);

        $loginID = session('loginID');

        // Store the selected class record ID in the session
        $selectedClassRecordID = $request->input('classRecordIDView');
        session(['selectedClassRecordID' => $selectedClassRecordID]);

        // Retrieve the user based on the login ID
        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        // $students = Student::where('classRecordID', $selectedClassRecordID)
        //     ->where('email', $user->login->email)
        //     ->first();

        $students = Student::where('classRecordID', $selectedClassRecordID)
            ->where('studentNo', $user->schoolIDNo)
            ->first();

        // Handle case where student is not found
        if (!$students) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Retrieve the first grading distribution
        $firstGradingDistribution = GradingDistribution::where('classRecordID', $selectedClassRecordID)
            ->first();

        // Check if a grading distribution was found
        if (!$firstGradingDistribution) {
            return redirect()->back()->with('error', 'No grading distribution type found for the selected class record.');
        }

        // Get the grading distribution type and term
        $firstGradingDistributionType = strtolower($firstGradingDistribution->gradingDistributionType);
        $gradingTerm = $firstGradingDistribution->term;

        // dd($firstGradingDistributionType);

        session(['gradingTerm' => $gradingTerm]);

        // Store the grading distribution type in the session
        session(['selectedTab' => $firstGradingDistributionType]);

        // Replace spaces with dashes for URL formatting
        $selectedfirstGradingDistributionType = str_replace(' ', '-', $firstGradingDistributionType);

        // Retrieve the student assessments for the selected grading term and class record
        // $studentAssessments = StudentAssessment::with('assessment')
        //     ->where('studentID', $students->studentID)
        //     ->where('classRecordID', $selectedClassRecordID)
        //     ->whereHas('assessment', function ($query) use ($gradingTerm) {
        //         $query->where('term', $gradingTerm);
        //     })
        //     ->get();

        // Redirect to the appropriate route
        $redirectUrl = route('student.class-record-info', ['gradingDistributionType' => $selectedfirstGradingDistributionType]);
        return redirect($redirectUrl);
    }

    public function storeStudentClassRecordIdGmail($classRecordID, $GradingType, $selectedAssessIDs)
    {
        session(['selectedClassRecordID' => $classRecordID]);

        $loginID = session('loginID');

        $user = Registration::with('login')
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // $student = Student::where('classRecordID', $classRecordID)
        //     ->where('email', $user->login->email)
        //     ->first();

        // $student = Student::where('classRecordID', $classRecordID)
        //     ->where('studentNo', $user->schoolIDNo)
        //     ->first();

        // if (!$student) {
        //     return redirect()->back()->with('error', 'Student not found.');
        // }

        $gradingDistribution = GradingDistribution::where('classRecordID', $classRecordID)
            ->where('gradingDistributionType', $GradingType)
            ->first();


        // dd($gradingDistribution);
        if (!$gradingDistribution) {
            return redirect()->back()->with('error', 'Grading distribution not found for the selected grading type.');
        }

        $formattedGradingType = str_replace(' ', '-', strtolower($GradingType));


        session(['gradingTerm' => $gradingDistribution->term]);

        // session(['selectedTab' => $formattedGradingType]);

        session(['selectedgradingDistributionType' => $formattedGradingType]);


        $selectedAssessIDsArray = explode(',', $selectedAssessIDs);


        // session(['selectedAssessmentID' => $selectedAssessIDs]);
        session(['selectedAssessmentID' => $selectedAssessIDsArray]);

        return redirect()->route('student.class-record-assessment-details', ['gradingDistributionType' => $formattedGradingType]);

        return redirect($redirectUrl);
    }

    public function storeStudentClassRecordIdNotif(Request $request)
    {
        session(['selectedClassRecordID' => $request->input('classRecordIDStudentNotif')]);

        $selectedAssessIDs = explode(',', $request->input('assessmentID'));


        session(['selectedAssessmentID' => $selectedAssessIDs]);


        $notifID = $request->input('notifIDStudent');
        $loginID = session('loginID');
        $user = Login::with('registration')->find($loginID);

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }

        $notification = $user->notifications->find($notifID);

        if ($notification) {
            // Update the read_at timestamp to mark the notification as read
            $notification->markAsRead();

            $notificationData = $notification->data;
            if (isset($notificationData['data']['url'])) {
                $redirectUrl = $notificationData['data']['url'];
                $gradingType = $notificationData['data']['gradingType'];
                // $gradingTerm = $notificationData['data']['gradingTerm'];

                $gradingDistributionType = strtolower($gradingType);
                $gradingDistributionType = strtolower(str_replace(' ', '-', $gradingDistributionType));


                // dd($gradingDistributionType);
                session(['selectedgradingDistributionType' => $gradingDistributionType]);
                session(['selectedTab' => $gradingDistributionType]);
                // session(['gradingTerm' => $gradingTerm]);



                return redirect($redirectUrl)->with('success', 'Notification marked as read.');
            } else {
                return redirect()->back()->withErrors('Notification URL not found.');
            }
        } else {
            // Handle the case where the notification is not found
            return redirect()->back()->withErrors('Notification not found.');
        }
    }


    public function displayAccountInfo()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['course.program', 'login.registration'])
            ->where('isArchived', 0)
            ->where('loginID', $loginID)
            ->latest('created_at')
            ->get();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->type = $notificationData['type'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();
        $studentData = [];

        foreach ($notifications as $notification) {
            $notificationData = json_decode($notification->data, true);

            $studentLoginID = $notificationData['data']['student_loginID'] ?? null;
            if ($studentLoginID) {
                $student = Registration::where('loginID', $studentLoginID)->first();
                if ($student) {
                    $notification->Fname = $student->Fname;
                    $notification->Lname = $student->Lname;
                } else {
                    $notification->Fname = 'Unknown';
                    $notification->Lname = 'Unknown';
                }
            } else {
                $notification->Fname = 'Unknown';
                $notification->Lname = 'Unknown';
            }
        }
        return view('settings-acc-info', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'notifications', 'unreadCount', 'classRecords'));
    }

    public function displayUpdatePassword()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['course.program', 'login.registration'])
            ->where('isArchived', 0)
            ->where('loginID', $loginID)
            ->latest('created_at')
            ->get();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->type = $notificationData['type'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();
        $studentData = [];

        foreach ($notifications as $notification) {
            $notificationData = json_decode($notification->data, true);

            $studentLoginID = $notificationData['data']['student_loginID'] ?? null;
            if ($studentLoginID) {
                $student = Registration::where('loginID', $studentLoginID)->first();
                if ($student) {
                    $notification->Fname = $student->Fname;
                    $notification->Lname = $student->Lname;
                } else {
                    $notification->Fname = 'Unknown';
                    $notification->Lname = 'Unknown';
                }
            } else {
                $notification->Fname = 'Unknown';
                $notification->Lname = 'Unknown';
            }
        }
        return view('settings-pass-info', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'notifications', 'unreadCount', 'classRecords'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword'
        ]);

        $user = Login::find($request->loginID);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'currentPassword' => ['Your current password is incorrect.']
                ]
            ], 422);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();
        
        $studentID = session('loginID');
        $studentInfo = Registration::where('loginID', $studentID)->first();

        AuditTrail::create([
            'record_id' => $studentID,
            'user' => $studentInfo->schoolIDNo,
            'action' => 'Update',
            'table_name' => 'Registration',
            'description' => "User {$studentInfo->schoolIDNo} change password",
            'action_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student password updated successfully!'
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $request->validate([
            'registrationID' => 'required|exists:registration_tbl,registrationID',
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Sname' => 'nullable|string|max:255',
            'salutation' => 'nullable|string|max:255',
        ]);

        // Find the registration record
        $registration = Registration::find($request->registrationID);

        if ($registration) {
            $registration->Fname = $request->Fname;
            $registration->Mname = $request->Mname;
            $registration->Lname = $request->Lname;
            $registration->Sname = $request->Sname;
            $registration->salutation = $request->salutation;
            $registration->save();

            return response()->json(['success' => true, 'message' => 'Personal information updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Registration not found'], 404);
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s.,!?]+$/',
        ]);

        $studentID = session('loginID');
        $selectedClassRecordID = session('selectedClassRecordID');

        Feedback::create([
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            'studentID' => $studentID,
            'loginID' => $request->input('loginID'),
            'classRecordID' => $selectedClassRecordID,
        ]);

        $classRecord = ClassRecord::find($selectedClassRecordID);
        $professorLoginID = $classRecord->loginID;

        $professor = Login::find($professorLoginID);

        $studentInfo = Registration::where('loginID', $studentID)->first();

        $professorInfo = Registration::where('loginID', $professorLoginID)->first();
        $fname = $professorInfo->Fname;
        $salutation = $professorInfo->salutation;



        Mail::to($professor->email)->send(new StudentFeedbackInformation(
            type: 'feedback',
            loginID: $professorLoginID,
            courseCode: $classRecord->course->courseCode,
            classRecordID: $selectedClassRecordID,
            studentID: $studentID,
            fname: $fname,
            salutation: $salutation,
        ));


        // $professor->notify(new StudentFeedback(
        //     type: 'feedback',
        //     loginID: $professorLoginID,
        //     courseCode: $classRecord->course->courseCode,
        //     classRecordID: $selectedClassRecordID,
        //     studentFullName: 'N/A', 
        //     studentID: $studentID,
        //     formattedGradingType: 'N/A', 
        //     notFormattedGradingType: 'N/A',
        //     tryGradingType: 'N/A',
        //     gradingTerm: 'N/A',
        //     fname: $fname,
        //     salutation: $salutation,
        // ));


        AuditTrail::create([
            'record_id' => $studentID,
            'user' => $studentInfo->schoolIDNo,
            'action' => 'Create',
            'table_name' => 'feedback_tbl',
            'description' => "Feedback submitted to {$salutation} {$fname}",
            'action_time' => Carbon::now(),
        ]);


        return response()->json(['success' => true, 'message' => 'Feedback submitted successfully!']);
    }

    public function displayFacultyActivityLog()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;

                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();


        return view('student.student-act-log', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount'));
    }

    public function getActLogsData(Request $request)
    {
        $loginID = session('loginID');

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');
        $orderColumn = $request->input('order.0.column', '2');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = ['action', 'description', 'action_time'];
        $orderColumnName = $columns[$orderColumn] ?? 'action_time';

        $query = AuditTrail::where('record_id', $loginID);

        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('action', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('action_time', 'like', "%{$searchValue}%");
            });
        }

        $totalFiltered = $query->count();

        $logs = $query->orderBy($orderColumnName, $orderDirection)
            ->orderBy('action_time', 'desc')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->record_id,
                    'user' => $log->user,
                    'action' => $log->action,
                    'table_name' => $log->table_name,
                    'old_value' => $log->old_value,
                    'new_value' => $log->new_value,
                    'description' => $log->description,
                    'action_time' => $log->action_time,
                ];
            });

        return response()->json([
            'data' => $logs,
            'recordsTotal' => AuditTrail::where('record_id', $loginID)->count(),
            'recordsFiltered' => $totalFiltered,
        ]);
    }



    public function displayStudentArchivedClassRecord()
    {

        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login', 'student'])
            ->whereHas('login', function ($query) {
                $query->where('role', 3);
            })
            ->where('loginID', $loginID)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $userinfo = $user;

        // $student = Student::where('studentNo', $userinfo->schoolIDNo)->first();

        // $students = Student::where('classRecordID', $classRecordID)
        //     ->where('studentNo', $user->schoolIDNo)
        //     ->first();

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
                $notification->selectedAssessIDs = $notificationData['data']['selectedAssessIDs'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('student.student-archived-record', [
            'loginID' => $loginID,
            'role' => $role,
            // 'students' => $student,
            'user' => $user,
            'userinfo' => $userinfo,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    // public function getStudentArchives(Request $request)
    // {
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);

    //     $loginID = session('loginID');
    //     $studentNo = session('studentNo');

    //     $students = Student::where('studentNo', $studentNo)->get();

    //     if ($students->isEmpty()) {
    //         return redirect()->back()->with('error', 'Student not found');
    //     }

    //     $classRecordIDs = $students->pluck('classRecordID')->unique();

    //     $schoolYear = null;
    //     $semester = null;

    //     $registration = Registration::where('loginID', $loginID)->first();

    //     if ($registration) {
    //         $admin = Admin::where('branch', $registration->branch)->first();

    //         if ($admin) {
    //             $schoolYear = $admin->schoolYear;
    //             $semester = $admin->semester;
    //         }
    //     }

    //     $total = ClassRecord::whereIn('classRecordID', $classRecordIDs)->count();
    //     $classRecords = ClassRecord::with(['course.program', 'login.registration', 'branchDetail'])
    //         ->where('isArchived', 1)
    //         ->orderByRaw("CASE 
    //         WHEN schoolYear = ? AND semester = ? THEN 0
    //         ELSE 1 
    //     END", [$schoolYear, $semester])
    //         ->orderBy('created_at', 'desc')
    //         ->offset($start)
    //         ->limit($length)
    //         ->get()
    //         ->map(function ($classRecord) {
    //             return [
    //                 'classRecordID' => $classRecord->classRecordID,
    //                 'courseName' => $classRecord->course->courseTitle ?? 'N/A',
    //                 'courseCode' => $classRecord->course->courseCode ?? 'N/A',
    //                 'programName' => $classRecord->course->program->programCode ?? 'N/A',
    //                 'branch' => $classRecord->branchDetail->branchDescription ?? 'N/A',
    //                 'schoolYear' => $classRecord->schoolYear ?? 'N/A',
    //                 'yearLevel' => $classRecord->yearLevel ?? 'N/A',
    //                 'semester' => $classRecord->semester ?? 'N/A',
    //                 'created_at' => $classRecord->created_at->format('Y-m-d H:i:s'),
    //             ];
    //         });

    //     return response()->json([
    //         'data' => $classRecords,
    //         'recordsTotal' => $total,
    //         'recordsFiltered' => $total,
    //     ]);
    // }

    public function getStudentArchives(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $loginID = session('loginID');
        $studentNo = session('studentNo');

        // Retrieve all students with the given studentNo
        $students = Student::where('studentNo', $studentNo)->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Extract all classRecordIDs associated with the students
        $classRecordIDs = $students->pluck('classRecordID');

        if ($classRecordIDs->isEmpty()) {
            return response()->json(['data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0]);
        }

        // Debugging
        // dd($classRecordIDs);

        // Get the current school year and semester
        $schoolYear = null;
        $semester = null;

        $registration = Registration::where('loginID', $loginID)->first();

        if ($registration) {
            $admin = Admin::where('branch', $registration->branch)->first();

            if ($admin) {
                $schoolYear = $admin->schoolYear;
                $semester = $admin->semester;
            }
        }

        // Fetch the total count of archived class records
        $total = ClassRecord::whereIn('classRecordID', $classRecordIDs)
            ->where('isArchived', 1)
            ->count();

        // Fetch the archived class records
        $classRecords = ClassRecord::with(['course.program', 'login.registration', 'branchDetail'])
            ->whereIn('classRecordID', $classRecordIDs)
            ->where('isArchived', 1)
            ->orderByRaw("CASE 
            WHEN schoolYear = ? AND semester = ? THEN 0
            ELSE 1 
        END", [$schoolYear, $semester])
            ->orderBy('created_at', 'desc')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($classRecord) {
                return [
                    'classRecordID' => $classRecord->classRecordID,
                    'courseName' => $classRecord->course->courseTitle ?? 'N/A',
                    'courseCode' => $classRecord->course->courseCode ?? 'N/A',
                    'programName' => $classRecord->course->program->programCode ?? 'N/A',
                    'branch' => $classRecord->branchDetail->branchDescription ?? 'N/A',
                    'schoolYear' => $classRecord->schoolYear ?? 'N/A',
                    'yearLevel' => $classRecord->yearLevel ?? 'N/A',
                    'semester' => $classRecord->semester ?? 'N/A',
                    'created_at' => $classRecord->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'data' => $classRecords,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }
}
