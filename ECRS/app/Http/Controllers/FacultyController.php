<?php

namespace App\Http\Controllers;

use App\Console\Commands\CheckPuptFacultySchedules;
use Illuminate\Support\Facades\DB;
use App\Imports\StudentImport;
use App\Models\Assessment;
use App\Models\ClassRecord;
use App\Models\Schedule;
use App\Models\Courses;
use App\Models\Login;
use App\Models\Programs;
use App\Models\Branch;
use App\Models\Grading;
use App\Models\GradingDistribution;
use Illuminate\Support\Facades\Storage;
use App\Models\Registration;
use App\Models\Admin;
use App\Models\Student;
use App\Models\StudentAssessment;
use App\Models\SubmittedFile;
use App\Notifications\FacultySendSemesterGrades;
use App\Notifications\FacultySendStudentCredentials;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use setasign\Fpdi\Fpdi as FpdiFpdi;
use setasign\Fpdi\Tfpdf\Fpdi;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Exports\SemesterGradeExport;
use App\Mail\StudentAccountCredentials;
use App\Mail\SubmitClassRecordReportEmail;
use App\Models\Feedback;
use App\Models\AuditTrail;
use App\Models\SuperAdmin;
use App\Notifications\BatchFacultySendStudentCredentials;
use App\Notifications\EmailNotificationAdminIntegration;
use App\Notifications\SendNotificationFacultyLoads;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Svg\Tag\Rect;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class FacultyController extends Controller
{

    // PAGES

    public function facultyClassRecordPage()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

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

        $classRecords = ClassRecord::with(['course.program', 'login.registration', 'branchDetail'])
            ->where('isArchived', 0)
            ->where('loginID', $loginID)
            ->orderByRaw("CASE 
                    WHEN schoolYear = ? AND semester = ? THEN 0
                    ELSE 1 
                END", [$schoolYear, $semester])
            ->orderBy('created_at', 'desc')
            ->get();

        // $notifications = DB::table('notifications')
        //     ->where('notifiable_id', $loginID)
        //     ->latest('created_at')
        //     ->get()
        //     ->map(function ($notification) {
        //         $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
        //         $notificationData = json_decode($notification->data, true);
        //         $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
        //         $notification->type = $notificationData['type'] ?? 'No message provided.';
        //         $notification->classRecordID = $notificationData['data']['classRecordID'] ?? null;
        //         return $notification;
        //     });

        // $unreadCount = $notifications->whereNull('read_at')->count();

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        // foreach ($notifications as $notification) {
        //     $notificationData = json_decode($notification->data, true);
        //     $studentLoginID = $notificationData['data']['student_loginID'] ?? null;

        //     if ($studentLoginID) {
        //         $student = Registration::where('loginID', $studentLoginID)->first();
        //         if ($student) {
        //             $notification->Fname = $student->Fname;
        //             $notification->Lname = $student->Lname;
        //         } else {
        //             $notification->Fname = 'Unknown';
        //             $notification->Lname = 'Unknown';
        //         }
        //     } else {
        //         $notification->Fname = 'Unknown';
        //         $notification->Lname = 'Unknown';
        //     }
        // }

        return view('faculty.faculty-class-record', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'unreadCountFeedback'));
    }




    public function displayCourseList()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Registration::with('login')->find($loginID);
        // $userinfo = $user;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $programs = Programs::byBranch()->get();

        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-courselist', compact('loginID', 'userinfo', 'user', 'role', 'programs', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }



    public function displayProgramList()
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');

        // $user = Registration::with('login')->find($loginID);
        // $userinfo = $user;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $programs = Programs::byBranch()->get();

        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-programlist', compact('loginID', 'userinfo', 'user', 'role', 'programs', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }


    public function displaySubmittedReports()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('registration')->find($loginID);
        // $userinfo = $user ? $user->registration : null;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $submittedFiles = SubmittedFile::with([
            'classrecord.login.registration',
            'classrecord.program',
            'classrecord.course'
        ])
            ->whereHas('classrecord', function ($query) use ($loginID) {
                $query->whereHas('login', function ($query) use ($loginID) {
                    $query->where('loginID', $loginID);
                });
            })
            ->get();

        $submittedData = $submittedFiles->map(function ($file) {
            return [
                'fileID' => $file->fileID,
                'file' => $file->file,
                'status' => $file->status,
                'classRecordID' => $file->classRecordID,
                'createdAt' => $file->created_at->toDateTimeString(), // Format datetime if needed
                'programTitle' => $file->classrecord->program->programTitle ?? 'N/A',
                'courseTitle' => $file->classrecord->course->courseTitle ?? 'N/A',
            ];
        });

        // Fetch notifications and unread count
        // $notifications = $user ? $user->notifications : collect();

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        return view('faculty.faculty-reports-submitted', compact('loginID', 'role', 'user', 'userinfo', 'submittedData', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }


    public function displayVerifiedReports()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $user = Login::with('registration')->find($loginID);
        // $userinfo = $user ? $user->registration : null;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $submittedFiles = SubmittedFile::with([
            'classrecord.login.registration',
            'classrecord.program',
            'classrecord.course'
        ])
            ->whereHas('classrecord', function ($query) use ($loginID) {
                $query->whereHas('login', function ($query) use ($loginID) {
                    $query->where('loginID', $loginID);
                });
            })
            ->where('status', 1)
            ->get();

        $submittedData = $submittedFiles->map(function ($file) {
            return [
                'fileID' => $file->fileID,
                'file' => $file->file,
                'status' => $file->status,
                'classRecordID' => $file->classRecordID,
                'createdAt' => $file->created_at,
                'updatedAt' => $file->updated_at,
                'programTitle' => $file->classrecord->program->programTitle ?? 'N/A',
                'courseTitle' => $file->classrecord->course->courseTitle ?? 'N/A',
            ];
        });

        // Fetch notifications and unread count
        // $notifications = $user ? $user->notifications : collect();

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        return view('faculty.faculty-reports-verified', compact('loginID', 'role', 'user', 'userinfo', 'submittedData', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }

    public function showClassRecordStudentInfo(Request $request)
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');
        $classRecordID = session('selectedClassRecordID');

        // dd($classRecordID);

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            // ->where('isArchived', 1)
            ->firstOrFail();

        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();
        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-class-record-info-stud', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'role' => $role,
            'branch' => $branch,
            'user' => $user,
            'userinfo' => $userinfo,
            'notifications' => $notifications,
            'gradingDistributions' => $gradingDistributions,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }


    public function getStudentInfoData(Request $request)
    {
        $classRecordID = session('selectedClassRecordID');

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            null,
            'studentNo',
            'studentLname',
            'studentFname',
            'studentMname',
            'registration.Sname',
            'email',
            'registration.isActive',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'studentLname';

        $classRecord = ClassRecord::with(['students.registration'])
            ->where('classRecordID', $classRecordID)
            ->firstOrFail();

        $students = $classRecord->students;

        if (!empty($searchValue)) {
            $students = $students->filter(function ($student) use ($searchValue) {
                return stripos(strtolower($student->studentNo), $searchValue) !== false ||
                    stripos(strtolower($student->studentLname), $searchValue) !== false ||
                    stripos(strtolower($student->studentFname), $searchValue) !== false ||
                    stripos(strtolower($student->email), $searchValue) !== false;
            });
        }

        $students = $students->sortBy(function ($student) use ($orderColumnName) {
            return strtolower(data_get($student, $orderColumnName, ''));
        }, SORT_REGULAR, $orderDirection === 'desc');

        $total = $students->count();

        $studentsData = $students
            ->slice($start, $length)
            ->map(function ($student) {
                return [
                    'studentID' => $student->studentID,
                    'studentNo' => $student->studentNo,
                    'studentLname' => $student->studentLname,
                    'studentFname' => $student->studentFname,
                    'studentMname' => $student->studentMname,
                    'Sname' => $student->registration->Sname ?? '',
                    'email' => $student->email,
                    'remarks' => $student->remarks ?? '',
                    'mobileNo' => $student->mobileNo ?? '',
                    'status' => $student->registration && $student->registration->isActive ? 'Active' : 'Inactive',
                    'isSentCredentials' => $student->registration->isSentCredentials ?? 0,
                ];
            });

        return response()->json([
            'data' => $studentsData->values(),
            'recordsTotal' => $classRecord->students->count(),
            'recordsFiltered' => $total,
        ]);
    }



    public function handleAssessmentType()
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');
        $classRecordID = session('selectedClassRecordID');
        $selectedTab = session('selectedTab');
        $selectedTabFormatted = str_replace('-', ' ', $selectedTab);
        $storedAssessmentType = session('assessmentType');

        // dd($storedAssessmentType);





        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            // ->where('isArchived', 0)
            ->firstOrFail();

        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();


        $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();


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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();
        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-grading-type-info', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'role' => $role,
            'branch' => $branch,
            'user' => $user,
            'userinfo' => $userinfo,
            'gradingDistribution' => $gradingDistribution,
            'gradingDistributions' => $gradingDistributions,
            // 'assessmentInformation' => $assessmentInformation,
            'storedAssessmentType' => $storedAssessmentType,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }


    // public function getAssessmentInfoData()
    // {
    //     $storedAssessmentType = session('assessmentType');
    //     $classRecordID = session('selectedClassRecordID');
    //     $gradingTerm = session('gradingTerm');

    //     $assessmentInformation = Assessment::where('term', $gradingTerm)
    //         ->where('assessmentType', $storedAssessmentType)
    //         ->where('classRecordID', $classRecordID)
    //         ->get();



    //     return response()->json($assessmentInformation);
    // }


    // public function getAssessmentInfoData()
    // {
    //     $storedAssessmentType = session('assessmentType');
    //     $classRecordID = session('selectedClassRecordID');
    //     $gradingTerm = session('gradingTerm');
    //     $selectedTab = session('selectedTab');
    //     $selectedTabFormatted = str_replace('-', ' ', $selectedTab);

    //     if (is_null($storedAssessmentType) || is_null($classRecordID) || is_null($gradingTerm)) {
    //         return response()->json(['error' => 'Missing session data'], 400);
    //     }

    //     $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();

    //     if (!$gradingDistribution) {
    //         return response()->json(['error' => 'Grading distribution not found'], 404);
    //     }

    //     $gradingDistributionType = $gradingDistribution->gradingDistributionType;

    //     $assessmentInformation = Assessment::where('term', $gradingTerm)
    //         ->where('assessmentType', $storedAssessmentType)
    //         ->where('classRecordID', $classRecordID)
    //         ->get()
    //         ->map(function ($assessment) {
    //             return [
    //                 'assessmentID' => $assessment->assessmentID,
    //                 'assessmentName' => $assessment->assessmentName,
    //                 'assessmentDate' => $assessment->assessmentDate,
    //                 'totalItem' => $assessment->totalItem,
    //                 'passingItem' => $assessment->passingItem,
    //                 'isPublished' => $assessment->isPublished,
    //             ];
    //         });

    //     $response = [
    //         'storedAssessmentType' => $storedAssessmentType,
    //         'gradingDistributionType' => $gradingDistributionType,
    //         'assessmentInformation' => $assessmentInformation,
    //     ];

    //     return response()->json($response);
    // }

    public function getAssessmentInfoData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '6');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            'assessmentName',
            'assessmentDate',
            'totalItem',
            'passingItem',
            'isPublished',
            'created_at',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'created_at';

        $storedAssessmentType = session('assessmentType');
        $classRecordID = session('selectedClassRecordID');
        $gradingTerm = session('gradingTerm');
        $selectedTab = session('selectedTab');
        $selectedTabFormatted = str_replace('-', ' ', $selectedTab);

        if (is_null($storedAssessmentType) || is_null($classRecordID) || is_null($gradingTerm)) {
            return response()->json(['error' => 'Missing session data'], 400);
        }

        $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();

        if (!$gradingDistribution) {
            return response()->json(['error' => 'Grading distribution not found'], 404);
        }

        $gradingDistributionType = $gradingDistribution->gradingDistributionType;

        $assessmentsQuery = Assessment::where('term', $gradingTerm)
            ->where('assessmentType', $storedAssessmentType)
            ->where('classRecordID', $classRecordID);

        if (!empty($searchValue)) {
            $assessmentsQuery = $assessmentsQuery->where(function ($query) use ($searchValue) {
                $query->whereRaw('LOWER(assessmentName) LIKE ?', ["%$searchValue%"])
                    ->orWhereRaw('CAST(totalItem AS CHAR) LIKE ?', ["%$searchValue%"])
                    ->orWhereRaw('CAST(passingItem AS CHAR) LIKE ?', ["%$searchValue%"]);
            });
        }

        $total = $assessmentsQuery->count();

        $assessments = $assessmentsQuery
            ->orderBy($orderColumnName, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($assessment) {
                return [
                    'assessmentID' => $assessment->assessmentID,
                    'assessmentName' => $assessment->assessmentName,
                    'assessmentDate' => $assessment->assessmentDate,
                    'totalItem' => $assessment->totalItem,
                    'passingItem' => $assessment->passingItem,
                    'isPublished' => $assessment->isPublished,
                ];
            });

        $response = [
            'data' => $assessments,
            'storedAssessmentType' => $storedAssessmentType,
            'gradingDistributionType' => $gradingDistributionType,
            'recordsTotal' => Assessment::where('term', $gradingTerm)
                ->where('assessmentType', $storedAssessmentType)
                ->where('classRecordID', $classRecordID)
                ->count(),
            'recordsFiltered' => $total,
        ];

        return response()->json($response);
    }





    public function facultyCreateClassRecord()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['course.program', 'login.registration']) // Include login.registration for professor's name
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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();
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


        return view('faculty.faculty-create-class-record', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'notifications', 'unreadCount', 'classRecords', 'unreadCountFeedback'));
    }


    public function facultyUpdateClassRecord($classRecordID)
    {
        $loginID = session('loginID');
        $role = session('role');

        // Retrieve user information
        $user = Registration::with(['login'])
            ->where('loginID', $loginID)
            ->where('role', 1) // Access the `role` field directly from `registration_tbl`
            ->first();

        $userinfo = $user;

        // Retrieve the specific class record along with associated data
        $classRecord = ClassRecord::with(['course.program', 'login.registration', 'grading', 'gradingDistribution']) // Load grading and distribution
            ->where('isArchived', 0)
            ->where('loginID', $loginID)
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$classRecord) {
            return redirect()->back()->with('error', 'Class record not found.');
        }

        // Fetch notifications
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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        // Append student names to notifications
        foreach ($notifications as $notification) {
            $notificationData = json_decode($notification->data, true);
            $studentLoginID = $notificationData['data']['student_loginID'] ?? null;

            if ($studentLoginID) {
                $student = Registration::where('loginID', $studentLoginID)->first();
                $notification->Fname = $student ? $student->Fname : 'Unknown';
                $notification->Lname = $student ? $student->Lname : 'Unknown';
            } else {
                $notification->Fname = 'Unknown';
                $notification->Lname = 'Unknown';
            }
        }

        return view('faculty.faculty-update-class-record', compact('loginID', 'userinfo', 'user', 'role', 'classRecord', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }


    //Updated Midterm Grades
    public function showClassRecordMidtermGrades()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $branch = session('branch');
        $classRecordID = session('selectedClassRecordID');

        // $user = Registration::with('login')->find($loginID);
        // $userinfo = $user;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $assessmentTypesCollection = Grading::where('classRecordID', $classRecordID)
            ->where('term', 1) // Ensure we are only getting assessment types for midterms
            ->distinct()
            ->pluck('assessmentType');
        $assessmentTypes = $assessmentTypesCollection->map(function ($type) {
            return strtolower(trim($type)); // Normalize to lowercase and trim whitespace
        })->toArray();

        // Fetch assessment data
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->where('term', 1) // Midterm assessments
            ->get(['assessmentType', 'percentage'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items, $key) {
                return $items->first(); // Get the percentage for each normalized assessment type
            });

        // Fetch total items data and normalize the case
        $totalItemsData = Assessment::where('classRecordID', $classRecordID)
            ->where('term', 1) // Term 1
            ->get(['assessmentType', 'totalItem'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->sum('totalItem') ?? 0;
            });

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0); // Default to 0
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData->toArray());
        // dd($combinedTotalItems);

        $totalPercentage = $assessmentData->sum('percentage');
        $sumTotalItems = $totalItemsData->sum();


        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            // ->where('term', 1)
            ->firstOrFail();

        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        $studentScores = [];
        foreach ($assessmentTypes as $type) {
            $assessmentIDs = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 1)
                ->pluck('assessmentID');


            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $classRecordID)
                ->selectRaw('studentID, SUM(score) as totalScore')
                ->groupBy('studentID')
                ->pluck('totalScore', 'studentID');

            // Calculate the final score for each student
            $finalScores = [];
            foreach ($scores as $studentID => $totalScore) {
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $assessmentData[$type]->percentage ?? 0;
                $finalScores[$studentID] = ($totalScore / $totalItem) * $percentage;
            }

            $studentScores[$type] = $finalScores;
        }

        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.midterm.faculty-class-record-info-grades', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'role' => $role,
            // 'branch' => $branch,
            'user' => $user,
            'userinfo' => $userinfo,
            'assessmentTypes' => $assessmentTypes,
            'assessmentData' => $assessmentData,
            'totalItemsData' => $combinedTotalItems,
            'totalPercentage' => $totalPercentage,
            'studentScores' => $studentScores,
            'sumTotalItems' => $sumTotalItems,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }
    //Updated Final Grades
    public function showClassRecordFinalsGrades()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $branch = session('branch');
        $classRecordID = session('selectedClassRecordID');

        // $user = Registration::with('login')->find($loginID);
        // $userinfo = $user;


        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $assessmentTypesCollection = Grading::where('classRecordID', $classRecordID)
            ->where('term', 2) // Ensure we are only getting assessment types for midterms
            ->distinct()
            ->pluck('assessmentType');
        $assessmentTypes = $assessmentTypesCollection->map(function ($type) {
            return strtolower(trim($type)); // Normalize to lowercase and trim whitespace
        })->toArray();

        // Fetch assessment data
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->where('term', 2) // Midterm assessments
            ->get(['assessmentType', 'percentage'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items, $key) {
                return $items->first(); // Get the percentage for each normalized assessment type
            });

        // Fetch total items data and normalize the case
        $totalItemsData = Assessment::where('classRecordID', $classRecordID)
            ->where('term', 2) // Term 1
            ->get(['assessmentType', 'totalItem'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->sum('totalItem') ?? 0;
            });

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0); // Default to 0
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData->toArray());
        // dd($combinedTotalItems);

        $totalPercentage = $assessmentData->sum('percentage');
        $sumTotalItems = $totalItemsData->sum();

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            // ->where('term', 2)
            ->firstOrFail();

        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        $studentScores = [];
        foreach ($assessmentTypes as $type) {
            $assessmentIDs = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 2) // Filter only for finals (term 2)
                ->pluck('assessmentID');

            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $classRecordID)
                ->selectRaw('studentID, SUM(score) as totalScore')
                ->groupBy('studentID')
                ->pluck('totalScore', 'studentID');

            // Calculate the final score for each student
            $finalScores = [];
            foreach ($scores as $studentID => $totalScore) {
                $totalItem = $combinedTotalItems[$type] ?? 0; // Avoid division by zero
                $percentage = $assessmentData[$type]->percentage ?? 0;

                if ($totalItem > 0) {
                    // Only calculate score if totalItem is greater than zero
                    $finalScores[$studentID] = ($totalScore / $totalItem) * $percentage;
                } else {
                    // Set score to zero if totalItem is zero
                    $finalScores[$studentID] = 0;
                }
            }

            $studentScores[$type] = $finalScores;
        }
        // dd($studentScores);
        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.finals.faculty-class-record-info-grades', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'role' => $role,
            // 'branch' => $branch,
            'user' => $user,
            'userinfo' => $userinfo,
            'assessmentTypes' => $assessmentTypes,
            'assessmentData' => $assessmentData,
            'totalItemsData' => $combinedTotalItems,
            'totalPercentage' => $totalPercentage,
            'studentScores' => $studentScores,
            'sumTotalItems' => $sumTotalItems,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }


    public function showAssessmentDetailsFinals($assessmentType)
    {
        $loginID = session('loginID');
        // $user = Registration::with('login')->find($loginID);

        $assessmentID = session('selectedAssessmentID');
        $classRecordID = session('selectedClassRecordID');

        if (!$assessmentID) {
            abort(404, 'No assessment ID found.');
        }

        // Retrieve assessment details
        $assessment = Assessment::find($assessmentID);

        if (!$assessment) {
            abort(404, 'Assessment not found.');
        }

        // Retrieve the term from the assessment
        $term = $assessment->term; // Ensure this term field exists in Assessment model

        $assessmentTypesQuery = Grading::where('classRecordID', $classRecordID)
            ->distinct();
        $assessmentTypes = $assessmentTypesQuery->pluck('assessmentType');

        // Retrieve class records with associated program, course, and students
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();

        // Find the assessment details using the assessmentID
        $details = Assessment::find($assessmentID);

        if (!$details) {
            abort(404, 'Details not found.');
        }

        // Retrieve scores for each student
        $studentScores = StudentAssessment::where('assessmentID', $assessmentID)
            ->where('classRecordID', $classRecordID)
            ->pluck('score', 'studentID');

        // Process notifications
        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.finals.faculty-class-record-info-details', compact(
            'details',
            'classRecords',
            'assessmentType',
            'assessmentTypes',
            'assessmentID',
            'classRecordID',
            'studentScores',
            'notifications',
            'unreadCount',
            'unreadCountFeedback'
        ));
    }

    public function showAssessmentDetails($gradingDistributionType)
    {
        $loginID = session('loginID');

        $assessmentID = session('selectedAssessmentID');
        $classRecordID = session('selectedClassRecordID');
        $gradingTerm = session('gradingTerm');
        $storedAssessmentType = session('assessmentType');
        $selectedTab = session('selectedTab');

        $selectedTabFormatted = str_replace('-', ' ', $selectedTab);


        if (!$assessmentID) {
            abort(404, 'No assessment ID found.');
        }

        // Retrieve assessment details
        $assessment = Assessment::find($assessmentID);

        if (!$assessment) {
            abort(404, 'Assessment not found.');
        }

        // Retrieve the term from the assessment
        $term = $assessment->term; // Ensure this term field exists in Assessment model

        $assessmentTypesQuery = Grading::where('classRecordID', $classRecordID)
            ->distinct();
        $assessmentTypes = $assessmentTypesQuery->pluck('assessmentType');

        $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();

        // Retrieve assessments associated with the class record ID
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();


        // if (!$gradingDistribution) {
        //     return redirect()->back()->with('error', 'Grading distribution not found');
        // }


        // $assessmentInformation = Assessment::where('term', $gradingTerm)
        //     ->where('assessmentType', $storedAssessmentType)
        //     ->where('classRecordID', $classRecordID)
        //     ->get();


        // Retrieve class records with associated program, course, and students
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            // ->where('isArchived', 0)
            ->firstOrFail();

        // Find the assessment details using the assessmentID
        $details = Assessment::find($assessmentID);

        if (!$details) {
            abort(404, 'Details not found.');
        }

        // Retrieve scores for each student
        // $studentScores = StudentAssessment::where('assessmentID', $assessmentID)
        //     ->where('classRecordID', $classRecordID)
        //     ->pluck('score', 'studentID');
        // ->get(['studentID', 'score', 'isRawScoreViewable']);

        // $studentAssessments = StudentAssessment::where('assessmentID', $assessmentID)
        //     ->where('classRecordID', $classRecordID)
        //     ->get(['studentID', 'score', 'isRawScoreViewable']);

        // dd($studentScores);

        // Process notifications
        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-class-record-info-details', compact(
            'details',
            'classRecords',
            // 'assessmentType',
            'storedAssessmentType',
            'assessmentTypes',
            'assessmentID',
            'classRecordID',
            'gradingDistribution',
            'gradingDistributions',
            // 'assessmentInformation',
            'storedAssessmentType',
            // 'studentScores',
            'notifications',
            'unreadCount',
            'unreadCountFeedback',
            // 'studentAssessments'
        ));
    }

    // public function getAssessmentDetailsData()
    // {
    //     $classRecordID = session('selectedClassRecordID');
    //     $assessmentID = session('selectedAssessmentID');
    //     $storedAssessmentType = session('assessmentType');
    //     $selectedTab = session('selectedTab');
    //     $selectedTabFormatted = str_replace('-', ' ', $selectedTab);
    //     $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();


    //     if (!$assessmentID) {
    //         abort(404, 'No assessment ID found.');
    //     }

    //     $assessment = Assessment::find($assessmentID);
    //     if (!$assessment) {
    //         abort(404, 'Assessment not found.');
    //     }

    //     $classRecords = ClassRecord::with(['program', 'course', 'students'])
    //         ->where('classRecordID', $classRecordID)
    //         // ->where('isArchived', 0)
    //         ->firstOrFail();

    //     $studentScoresAndRemarks = StudentAssessment::where('assessmentID', $assessmentID)
    //         ->where('classRecordID', $classRecordID)
    //         ->get()
    //         ->mapWithKeys(function ($item) {
    //             return [
    //                 $item->studentID => [
    //                     'score' => $item->score,
    //                     'remarks' => $item->remarks, 
    //                     'isRawScoreViewable' => $item->isRawScoreViewable,
    //                 ]
    //             ];
    //         });

    //     $students = $classRecords->students->map(function ($student) use ($studentScoresAndRemarks) {
    //         return [
    //             'studentNo' => $student->studentNo,
    //             'studentID' =>  $student->studentID,
    //             'studentFname' => $student->studentFname,
    //             'studentLname' => $student->studentLname,
    //             'score' => $studentScoresAndRemarks[$student->studentID]['score'] ?? null,
    //             'remarks' => $studentScoresAndRemarks[$student->studentID]['remarks'] ?? null,  // Get remarks
    //             'isRawScoreViewable' => $studentScoresAndRemarks[$student->studentID]['isRawScoreViewable'] ?? null,
    //         ];
    //     });

    //     $response = [
    //         'storedAssessmentType' => $storedAssessmentType,
    //         'gradingDistributionType' => $gradingDistribution->gradingDistributionType ?? null,
    //         'term' => $gradingDistribution->term ?? null,
    //         'assessmentID' => $assessmentID,
    //         'classRecordID' => $classRecordID,
    //         'totalItem' => $assessment->totalItem, 
    //         'passingItem' => $assessment->passingItem,  
    //         'students' => $students,
    //     ];
    //     return response()->json($response);
    // }

    public function getAssessmentDetailsData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '2');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            'studentNo',
            'studentFname',
            'studentLname',
            'score',
            'remarks',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'studentLname';

        $classRecordID = session('selectedClassRecordID');
        $assessmentID = session('selectedAssessmentID');
        $storedAssessmentType = session('assessmentType');
        $selectedTab = session('selectedTab');
        $selectedTabFormatted = str_replace('-', ' ', $selectedTab);

        if (is_null($assessmentID)) {
            return response()->json(['error' => 'No assessment ID found.'], 404);
        }

        $gradingDistribution = GradingDistribution::where('gradingDistributionType', $selectedTabFormatted)->first();

        $assessment = Assessment::find($assessmentID);
        if (!$assessment) {
            return response()->json(['error' => 'Assessment not found.'], 404);
        }

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->firstOrFail();

        $studentScoresAndRemarks = StudentAssessment::where('assessmentID', $assessmentID)
            ->where('classRecordID', $classRecordID)
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->studentID => [
                        'score' => $item->score,
                        'remarks' => $item->remarks,
                        'isRawScoreViewable' => $item->isRawScoreViewable,
                    ],
                ];
            });

        $studentsQuery = $classRecords->students;

        if (!empty($searchValue)) {
            $studentsQuery = $studentsQuery->filter(function ($student) use ($searchValue) {
                return stripos(strtolower($student->studentNo), $searchValue) !== false ||
                    stripos(strtolower($student->studentFname), $searchValue) !== false ||
                    stripos(strtolower($student->studentLname), $searchValue) !== false;
            });
        }

        $total = $studentsQuery->count();

        $students = $studentsQuery
            ->sortBy(function ($student) use ($orderColumnName, $studentScoresAndRemarks) {
                if (in_array($orderColumnName, ['score', 'remarks'])) {
                    return strtolower($studentScoresAndRemarks[$student->studentID][$orderColumnName] ?? '');
                }
                return strtolower($student->$orderColumnName ?? '');
            }, SORT_REGULAR, $orderDirection === 'desc')
            ->slice($start, $length)
            ->map(function ($student) use ($studentScoresAndRemarks) {
                return [
                    'studentNo' => $student->studentNo,
                    'studentID' => $student->studentID,
                    'studentFname' => $student->studentFname,
                    'studentLname' => $student->studentLname,
                    'score' => $studentScoresAndRemarks[$student->studentID]['score'] ?? null,
                    'remarks' => $studentScoresAndRemarks[$student->studentID]['remarks'] ?? null,
                    'isRawScoreViewable' => $studentScoresAndRemarks[$student->studentID]['isRawScoreViewable'] ?? null,
                ];
            });

        $response = [
            'data' => $students->values(),
            'storedAssessmentType' => $storedAssessmentType,
            'gradingDistributionType' => $gradingDistribution->gradingDistributionType ?? null,
            'term' => $gradingDistribution->term ?? null,
            'assessmentID' => $assessmentID,
            'classRecordID' => $classRecordID,
            'totalItem' => $assessment->totalItem,
            'passingItem' => $assessment->passingItem,
            'recordsTotal' => $classRecords->students->count(),
            'recordsFiltered' => $total,
        ];

        return response()->json($response);
    }


    public function showClassRecordGrades()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $branch = session('branch');
        $classRecordID = session('selectedClassRecordID');

        $gradingTerm = session('gradingTerm');

        // dd($gradingTerm);

        // $user = Registration::with('login')->find($loginID);
        // $userinfo = $user;

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $assessmentTypesCollection = Grading::where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm) // Ensure we are only getting assessment types for midterms
            ->distinct()
            ->pluck('assessmentType');
        $assessmentTypes = $assessmentTypesCollection->map(function ($type) {
            return strtolower(trim($type)); // Normalize to lowercase and trim whitespace
        })->toArray();


        // $gradingDistribution = GradingDistribution::where('gradingDistributionType', $gradingDistributionType)->first();

        // Retrieve assessments associated with the class record ID
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)
            ->get();

        $gradingTitle = GradingDistribution::where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm)
            ->get();

        // if (!$gradingDistribution) {
        //     return redirect()->back()->with('error', 'Grading distribution not found');
        // }


        // Fetch assessment data
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm) // Midterm assessments
            ->get(['assessmentType', 'percentage', 'gradingID'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items, $key) {
                return $items->first(); // Get the percentage for each normalized assessment type
            });

        // Fetch total items data and normalize the case
        $totalItemsData = Assessment::where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm) // Term 1
            ->get(['assessmentType', 'totalItem'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->sum('totalItem') ?? 0;
            });

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0); // Default to 0
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData->toArray());
        // dd($combinedTotalItems);

        $totalPercentage = $assessmentData->sum('percentage');
        $sumTotalItems = $totalItemsData->sum();


        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            // ->where('isArchived', 0)
            // ->where('term', 1)
            ->firstOrFail();

        // $notifications = $user->notifications;

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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        $studentScores = [];
        foreach ($assessmentTypes as $type) {
            $assessmentIDs = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->where('term', $gradingTerm)
                ->pluck('assessmentID');


            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $classRecordID)
                ->selectRaw('studentID, SUM(score) as totalScore')
                ->groupBy('studentID')
                ->pluck('totalScore', 'studentID');

            // Calculate the final score for each student
            $finalScores = [];
            foreach ($scores as $studentID => $totalScore) {
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $assessmentData[$type]->percentage ?? 0;
                $finalScores[$studentID] = ($totalScore / $totalItem) * $percentage;
            }

            $studentScores[$type] = $finalScores;
        }

        $studentData = [];
        foreach ($notifications as $notification) {
            $studentLoginID = $notification->data['data']['student_loginID'] ?? null;
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

        return view('faculty.faculty-class-record-info-grades', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'role' => $role,
            // 'branch' => $branch,
            'user' => $user,
            'userinfo' => $userinfo,
            'gradingDistributions' => $gradingDistributions,
            'gradingTitle' => $gradingTitle,
            'assessmentTypes' => $assessmentTypes,
            'assessmentData' => $assessmentData,
            'totalItemsData' => $combinedTotalItems,
            'totalPercentage' => $totalPercentage,
            'studentScores' => $studentScores,
            'sumTotalItems' => $sumTotalItems,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }

    public function updatePercentages(Request $request)
    {
        $gradingData = $request->input('gradingData');

        foreach ($gradingData as $data) {
            // Find the Grading record by gradingID and update the percentage
            $grading = Grading::find($data['gradingID']);
            if ($grading) {
                $grading->percentage = $data['percentage'];
                $grading->save();
            }
        }

        return response()->json(['message' => 'Percentages updated successfully']);
    }

    public function showClassRecordSemesterGrade(Request $request)
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID');

        // Fetch user information
        $user = Registration::with('login')
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // Get assessment types
        $assessmentTypes = Grading::where('classRecordID', $classRecordID)
            ->distinct()
            ->pluck('assessmentType');

        // Fetch class record with students
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->firstOrFail();

        // Fetch grading distributions for all terms
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)
            ->get();

        // Group by term for easy access
        $termDistribution = $gradingDistributions->groupBy('term');

        // Compute grades dynamically based on the number of terms
        $grades = DB::table('student_assessment_tbl AS sa')
            ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
            ->join('grading_tbl AS g', function ($join) use ($classRecordID) {
                $join->on('a.assessmentType', '=', 'g.assessmentType')
                    ->on('a.term', '=', 'g.term')
                    ->where('g.classRecordID', '=', $classRecordID);
            })
            ->select(
                'sa.studentID',
                'a.term',
                DB::raw('SUM(sa.score) / SUM(a.totalItem) * 100 * MAX(g.percentage) / 100 AS assessmentGrade')
            )
            ->groupBy('sa.studentID', 'a.term', 'a.assessmentType')
            ->get()
            ->groupBy('studentID')
            ->map(function ($grades, $studentID) use ($termDistribution, $classRecordID) {
                $termGrades = [];
                $semestralGrade = 0;
                $isIncomplete = false;

                foreach ([1, 2, 3] as $term) {
                    $termAssessments = $grades->where('term', $term);

                    // Check if any score is missing or zero for the term within the specific class record
                    $missingOrZeroScore = DB::table('assessment_tbl AS a')
                        ->leftJoin('student_assessment_tbl AS sa', function ($join) use ($studentID) {
                            $join->on('a.assessmentID', '=', 'sa.assessmentID')
                                ->where('sa.studentID', '=', $studentID);
                        })
                        ->where('a.term', $term)
                        ->where('a.classRecordID', '=', $classRecordID) // Restrict to the current class record
                        ->where(function ($query) {
                            $query->whereNull('sa.score');
                        })
                        ->exists();

                    $termGrade = $termAssessments->sum('assessmentGrade');
                    $termPercentage = $termDistribution[$term][0]->gradingDistributionPercentage ?? 0;

                    // If any score is missing or zero, mark the term as 'INC'
                    if ($missingOrZeroScore) {
                        $termGrades["term{$term}Grade"] = 'Not set';
                        $isIncomplete = true;
                    } else {
                        $termGrades["term{$term}Grade"] = number_format($termGrade, 2);
                        $semestralGrade += $termGrade * ($termPercentage / 100);
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

                return $termGrades;
            });



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
                return $notification;
            });


        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        return view('faculty.faculty-class-record-semester-grade', [
            'loginID' => $loginID,
            'classRecords' => $classRecords,
            'userinfo' => $userinfo,
            'gradingDistributions' => $gradingDistributions,
            'assessmentTypes' => $assessmentTypes,
            'grades' => $grades,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback
        ]);
    }

    public function displayAccountInfo()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $classRecords = ClassRecord::with(['course.program', 'login.registration']) // Include login.registration for professor's name
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
                return $notification;
            });
        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();
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
        return view('settings-acc-info', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'notifications', 'unreadCount', 'classRecords', 'unreadCountFeedback'));
    }


    public function displayUpdatePassword()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();

        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

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
        return view('settings-pass-info', compact('loginID', 'userinfo', 'user', 'role', 'classRecords', 'notifications', 'unreadCount', 'classRecords', 'unreadCountFeedback'));
    }

    public function feedbackStudentPage()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $feedbacks = Feedback::where('loginID', $loginID)
            ->with('student')
            ->orderBy('created_at', 'desc') // Order feedbacks by descending creation date
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
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        foreach ($feedbacks as $feedback) {
            $studentID = $feedback->studentID;

            $student = Registration::where('loginID', $studentID)->first();

            if ($student) {
                $feedback->student_name = $student->Fname . ' ' . $student->Lname;
            } else {
                $feedback->student_name = 'Unknown Student';
            }
        }

        return view('faculty.faculty-feedback-students', compact('loginID', 'userinfo', 'user', 'role', 'feedbacks', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }



    //FUNCTIONS

    public function facultyClassRecordData()
    {
        $loginID = session('loginID');
        $classRecords = ClassRecord::with(['course.program'])
            ->where('loginID', $loginID)
            ->where('isArchived', 0)
            ->latest('created_at')
            ->get();

        return response()->json($classRecords);
    }

    public function getPrograms($branchID)
    {
        $programs = Programs::where('branch', $branchID)->get();
        return response()->json(['programs' => $programs]);
    }

    public function getBranches()
    {
        // Fetch all branches
        $branches = Branch::all();
        // Return branches as JSON
        return response()->json([
            'branches' => $branches
        ]);
    }

    public function filterByProgram(Request $request)
    {
        $programID = $request->input('programID');
        $classRecords = ClassRecord::whereHas('course', function ($query) use ($programID) {
            $query->where('programID', $programID);
        })->get();

        return view('class-records.index', ['classRecords' => $classRecords]);
    }


    public function getCourses($programID)
    {
        $courses = Courses::where('programID', $programID)->get();
        return response()->json(['courses' => $courses]);
    }

    public function storeClassrecord(Request $request)
    {
        try {
            $rules = [
                'schoolYear' => 'required|string',
                'semester' => 'required|integer',
                'yearLevel' => 'required|string',
                'template' => 'nullable|string',
                'recordType' => 'required|integer',
                'branch' => 'required|integer',
                'isSubmitted' => 'integer',
                'programID' => 'required|integer',
                'courseID' => 'required|integer',
                'gradingDistributions' => 'required|array',
                'gradingDistributions.*.gradingDistributionType' => 'required|string',
                'gradingDistributions.*.gradingDistributionPercentage' => 'required|numeric|min:0|max:100',
                'gradingDistributions.*.term' => 'required|in:1,2,3',
                'grading' => 'required|array',
                'grading.*.assessmentType' => 'required|string',
                'grading.*.term' => 'required|in:1,2,3',
                'grading.*.percentage' => 'required|numeric|min:0|max:100',
                'grading.*.isExamination' => 'required|in:true,false,0,1',
                'schedules' => 'required|json',
            ];

            if ($request->hasFile('classImg')) {
                $rules['classImg'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            }

            $validatedData = $request->validate($rules);
            $imagePath = null;

            if ($request->hasFile('classImg')) {
                $file = $request->file('classImg');
                $fileName = $file->getClientOriginalName();
                $directory = 'class_images';
                $fullPath = public_path($directory . '/' . $fileName);

                if (!file_exists(public_path($directory))) {
                    mkdir(public_path($directory), 0755, true);
                }

                $file->move(public_path($directory), $fileName);
                $imagePath = $directory . '/' . $fileName;
            }

            // Store class record
            $classRecord = ClassRecord::create([
                'schoolYear' => $validatedData['schoolYear'],
                'semester' => $validatedData['semester'],
                'yearLevel' => $validatedData['yearLevel'],
                'classImg' => $imagePath,
                'template' => $validatedData['template'] ?? null,
                'recordType' => $validatedData['recordType'],
                'branch' => $validatedData['branch'],
                'isSubmitted' => 0,
                'isArchived' => 0,
                'programID' => $validatedData['programID'],
                'courseID' => $validatedData['courseID'],
                'loginID' => session('loginID')
            ]);

            if (!$classRecord || !$classRecord->classRecordID) {
                throw new \Exception('Failed to create ClassRecord');
            }

            $classRecord = ClassRecord::with(['program', 'course'])->find($classRecord->classRecordID);

            if (!$classRecord) {
                throw new \Exception('Failed to retrieve ClassRecord');
            }

            // Retrieve program code, year level, and course code
            $programCode = $classRecord->program->programCode ?? 'N/A';
            $yearLevel = $classRecord->yearLevel;
            $courseCode = $classRecord->course->courseCode ?? 'N/A';
            $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

            $userAdmin = Login::with('registration')
                ->where('loginID', session('loginID'))
                ->first();

            $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;
            $newValues = json_encode($classRecord->getAttributes());

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'class_record_tbl',
                'new_value' => $newValues,
                'description' => "Class Record Created Successfully: " . $classRecordDescription,
                'action_time' => Carbon::now(),
            ]);

            // Store schedule data
            $scheduleDescriptions = [];
            $schedules = json_decode($validatedData['schedules'], true);
            foreach ($schedules as $schedule) {
                Schedule::create([
                    'scheduleDay' => $schedule['day'],
                    'scheduleTime' => $schedule['time'],
                    'classRecordID' => $classRecord->classRecordID
                ]);
                $scheduleDescriptions[] = "{$schedule['day']} ({$schedule['time']})";
            }

            $scheduleDescString = implode(', ', $scheduleDescriptions);

            // Add single audit trail entry for schedules
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'schedule_tbl',
                'new_value' => json_encode($schedules),
                'description' => "Schedules Created for Class Record ID {$classRecordDescription}: {$scheduleDescString}",
                'action_time' => Carbon::now(),
            ]);


            // Store grading distribution data
            $gradingDescriptions = [];
            foreach ($validatedData['gradingDistributions'] as $distribution) {
                GradingDistribution::create([
                    'gradingDistributionType' => $distribution['gradingDistributionType'],
                    'gradingDistributionPercentage' => $distribution['gradingDistributionPercentage'],
                    'classRecordID' => $classRecord->classRecordID,
                    'term' => $distribution['term']
                ]);
                $gradingDescriptions[] = "{$distribution['gradingDistributionType']} ({$distribution['gradingDistributionPercentage']}%) for Term {$distribution['term']}";
            }

            // Combine grading descriptions into a single string
            $gradingDescString = implode(', ', $gradingDescriptions);

            // Add single audit trail entry for grading distributions
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'grading_distribution_tbl',
                'new_value' => json_encode($validatedData['gradingDistributions']),
                'description' => "Grading Distributions Created for Class Record {$classRecordDescription}: {$gradingDescString}",
                'action_time' => Carbon::now(),
            ]);

            // Store grading data
            $gradeDesc = [];
            foreach ($validatedData['grading'] as $grade) {
                Grading::create([
                    'assessmentType' => $grade['assessmentType'],
                    'term' => $grade['term'],
                    'percentage' => $grade['percentage'],
                    'isExamination' => filter_var($grade['isExamination'], FILTER_VALIDATE_BOOLEAN),
                    'classRecordID' => $classRecord->classRecordID
                ]);
                $gradeDesc[] = "{$grade['assessmentType']} ({$grade['percentage']}%) for Term {$grade['term']}";
            }
            $gradeDescString = implode(', ', $gradeDesc);

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'grade_tbl',
                'new_value' => json_encode($validatedData['grading']), // This can store the grading data if needed
                'description' => "Grade Percentage Created for Class Record {$classRecordDescription}: {$gradeDescString}",
                'action_time' => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Class record created successfully.', 'redirect_url' => url('faculty/class-record')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('ClassRecord creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json(['success' => false, 'message' => 'Class record creation failed: ' . $e->getMessage()], 500);
        }
    }

    public function storeClassRecordIntegration(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->hasFile('jsonFile')) {
                $file = $request->file('jsonFile');
                $data = json_decode(file_get_contents($file), true);
            } else {
                $data = $request->input('pupt_faculty_schedules');
            }

            Log::info('Request data:', $request->all());

            if (!$data) {
                return response()->json(['error' => 'Invalid JSON input'], 400);
            }

            $schedulesData = $data['pupt_faculty_schedules'];

            Log::info('Schedules data structure:', $schedulesData);

            if (!isset($schedulesData['academic_year_start']) || !isset($schedulesData['academic_year_end']) || !isset($schedulesData['semester'])) {
                return response()->json(['error' => 'Missing required academic year or semester information'], 400);
            }

            $academicYearStart = $schedulesData['academic_year_start'];
            $academicYearEnd = $schedulesData['academic_year_end'];
            $schoolYear = $academicYearStart . '-' . $academicYearEnd;
            $semester = $schedulesData['semester'];

            $existingClassRecord = ClassRecord::where('schoolYear', $schoolYear)
                ->where('semester', $semester)
                ->exists();

            if ($existingClassRecord) {
                $semesterName = match ($semester) {
                    1 => '1st Semester',
                    2 => '2nd Semester',
                    3 => 'Summer Semester',
                    default => 'Unknown Semester',
                };

                return response()->json([
                    'error' => "Class records already exist for the school year $schoolYear $semesterName."
                ], 400);
            }

            foreach ($schedulesData['faculties'] as $faculty) {
                if (!isset($faculty['faculty_code'])) {
                    return response()->json(['error' => 'Missing faculty code'], 400);
                }

                $facultyCode = $faculty['faculty_code'];
                $loginID = Registration::where('schoolIDNo', $facultyCode)->value('loginID');

                if (!$loginID) {
                    return response()->json(['error' => "Invalid faculty code for $facultyCode"], 404);
                }

                if (!isset($faculty['schedules'][0])) {
                    return response()->json(['error' => "No schedules found for faculty: $facultyCode"], 400);
                }

                foreach ($faculty['schedules'] as $schedule) {
                    // Validate program code
                    $programID = Programs::where('programCode', $schedule['program_code'])
                        ->where('branch', 1)
                        ->value('programID');

                    if (!$programID) {
                        DB::rollBack(); // Roll back transaction
                        return response()->json(['error' => "Invalid program code for faculty: $facultyCode"], 404);
                    }

                    // Validate course code
                    $courseID = Courses::where('courseCode', $schedule['course_details']['course_code'])
                        ->where('programID', $programID)
                        ->value('courseID');

                    if (!$courseID) {
                        DB::rollBack(); // Roll back transaction
                        return response()->json(['error' => "Invalid course code for faculty: $facultyCode"], 404);
                    }

                    // Validate time
                    $scheduleTime = $schedule['start_time'] . '-' . $schedule['end_time'];
                    if (!strtotime($schedule['start_time']) || !strtotime($schedule['end_time'])) {
                        DB::rollBack(); // Roll back transaction
                        return response()->json(['error' => "Invalid schedule time for faculty: $facultyCode"], 400);
                    }
                }
            }

            foreach ($schedulesData['faculties'] as $faculty) {
                $facultyCode = $faculty['faculty_code'];
                $loginID = Registration::where('schoolIDNo', $facultyCode)->value('loginID');
                $semesterName = match ($semester) {
                    1 => '1st Semester',
                    2 => '2nd Semester',
                    3 => 'Summer Semester',
                    default => 'Unknown Semester',
                };

                foreach ($faculty['schedules'] as $schedule) {
                    $yearLevel = $schedule['year_level'] . '-' . $schedule['section_name'];
                    $programID = Programs::where('programCode', $schedule['program_code'])
                        ->where('branch', 1)
                        ->value('programID');
                    $courseID = Courses::where('courseCode', $schedule['course_details']['course_code'])
                        ->where('programID', $programID)
                        ->value('courseID');

                    $classRecord = ClassRecord::create([
                        'schoolYear' => $schoolYear,
                        'semester' => $semester,
                        'yearLevel' => $yearLevel,
                        'branch' => 1,
                        'isSubmitted' => 0,
                        'isArchived' => 0,
                        'programID' => $programID,
                        'courseID' => $courseID,
                        'loginID' => $loginID,
                    ]);

                    $scheduleTime = $schedule['start_time'] . '-' . $schedule['end_time'];

                    Schedule::create([
                        'scheduleDay' => $schedule['day'],
                        'scheduleTime' => $scheduleTime,
                        'classRecordID' => $classRecord->classRecordID,
                    ]);

                    $professor = Login::find($loginID);

                    if ($professor) {
                        $type = 'faculty_loads';
                        $course = $schedule['course_details']['course_code'];
                        $professor->notify(new SendNotificationFacultyLoads($type, $semesterName, $schoolYear, $course));
                    }
                }
            }

            DB::commit();

            $userAdmin = Login::with('admin')
                ->where('loginID', session('loginID'))
                ->first();

            $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'schedule_tbl',
                'new_value' => '',
                'description' => "Class records created successfully",
                'action_time' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Class records created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back transaction on error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function adminFacultyLoads()
    {
        try {
            $loginID = session('loginID');
            $role = session('role');

            $user = Admin::with(['login'])
                ->where('loginID', $loginID)
                ->first();

            $userinfo = $user;

            $adminBranch = $user->branch ?? null;

            // Fetch notifications
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $loginID)
                ->latest('created_at')
                ->get()
                ->map(function ($notification) {
                    $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                    $notificationData = json_decode($notification->data, true);
                    $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                    $notification->fileID = $notificationData['data']['fileID'] ?? null;
                    return $notification;
                });

            $unreadCount = $notifications->whereNull('read_at')->count();

            // Fetch data from the PUPT API
            $secretKey = env('PUPT_API_SECRET');
            $timestamp = time();
            $nonce = bin2hex(random_bytes(16));
            $method = 'GET';
            $url = 'https://api.pupt-flss.com/api/external/ecrs/v1/pupt-faculty-schedules';
            $body = '';
            $message = $method . '|' . $url . '|' . $body . '|' . $timestamp . '|' . $nonce;
            $signature = hash_hmac('sha256', $message, $secretKey);

            $response = Http::withHeaders([
                'X-HMAC-Timestamp' => $timestamp,
                'X-HMAC-Nonce' => $nonce,
                'X-HMAC-Signature' => $signature,
            ])->get($url);

            if (!$response->successful()) {
                return view('admin.admin-faculty-loads', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'adminBranch'))->with('error', 'Failed to fetch data from PUPT API');
            }

            $data = $response->json();

            // Validate JSON data
            if (!isset($data['pupt_faculty_schedules'])) {
                return view('admin.admin-faculty-loads', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'adminBranch'))->with('error', 'Invalid or missing JSON data');
            }

            // Extract schedules data
            $schedulesData = $data['pupt_faculty_schedules'];

            // Log the schedules data for debugging
            Log::info('Retrieved schedules data:', $schedulesData);

            return view('admin.admin-faculty-loads', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'adminBranch'))
                ->with('message', 'Class record data retrieved successfully')
                ->with('data', $schedulesData);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error view
            return view('admin.admin-faculty-loads', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'adminBranch'))->with('error', $e->getMessage());
        }
    }

    public function facultyLoadPage()
    {
        return view('admin.admin-faculty-loads');
        // ->with('message', 'Class record data retrieved successfully')
        // ->with('data', $schedulesData);
    }


    public function fetchPuptFacultySchedules()
    {
        $secretKey = env('PUPT_API_SECRET');
        $timestamp = time();
        $nonce = bin2hex(random_bytes(16));
        $method = 'GET';
        $url = 'https://api.pupt-flss.com/api/external/ecrs/v1/pupt-faculty-schedules';
        $body = '';
        $message = $method . '|' . $url . '|' . $body . '|' . $timestamp . '|' . $nonce;
        $signature = hash_hmac('sha256', $message, $secretKey);

        $response = Http::withHeaders([
            'X-HMAC-Timestamp' => $timestamp,
            'X-HMAC-Nonce' => $nonce,
            'X-HMAC-Signature' => $signature,
        ])->get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Failed to fetch data from PUPT API'], $response->status());
        }
    }

    // public function fetchPuptFacultySchedules()
    // {
    //     $dummyJsonPath = public_path('dummy_json_file.json');
    //     $dummyData = json_decode(file_get_contents($dummyJsonPath), true);

    //     if (!$dummyData) {
    //         return response()->json(['error' => 'Invalid JSON file format'], 400);
    //     }

    //     $newDataHash = hash('sha256', json_encode($dummyData['pupt_faculty_schedules']));

    //     $storedHash = Cache::get('pupt_faculty_schedules_hash');

    //     if (!$storedHash || $newDataHash !== $storedHash) {
    //         Cache::put('pupt_faculty_schedules_hash', $newDataHash);

    //         $this->notifyAdmin($dummyData['pupt_faculty_schedules']);
    //     }

    //     //return response()->json(['message' => 'Dummy schedules checked successfully']);
    //     return response()->json($dummyData['pupt_faculty_schedules']);
    // }


    private function hasUpdates($existingData, $newData)
    {
        // Implement logic to compare existing data and new data
        return json_encode($existingData) !== json_encode($newData);
    }

    private function notifyAdmin($data)
    {
        $adminEmail = 'admin@example.com';

        Notification::route('mail', $adminEmail)->notify(new EmailNotificationAdminIntegration($data));
    }


    // public function fetchPuptFacultySchedules()
    // {
    //     // Path to the dummy JSON file
    //     $dummyJsonPath = public_path('dummy_json_file.json');

    //     // Dispatch the job to check the faculty schedules
    //     CheckPuptFacultySchedules::dispatch($dummyJsonPath);

    //     return response()->json(['message' => 'Checking schedules in the background']);
    // }




    public function getClassRecord(Request $request)
    {
        $classRecordID = $request->input('id');

        // Fetch the class record without branch relationship
        $classRecord = ClassRecord::with([
            'program:programID,programTitle',
            'course'
        ])->find($classRecordID);

        if ($classRecord) {
            // Set the class image URL
            $classRecord->classImgUrl = $classRecord->classImg ? url($classRecord->classImg) : 'https://via.placeholder.com/150';

            // Ensure program data is correctly structured
            $classRecord->program = [
                'programID' => $classRecord->program->programID,
                'programTitle' => $classRecord->program->programTitle
            ];

            // Manually fetch the branch data using the branch field
            $branch = Branch::find($classRecord->branch);
            if ($branch) {
                $classRecord->branch = [
                    'branchID' => $branch->branchID,
                    'branchDescription' => $branch->branchDescription
                ];
            } else {
                // Handle case where branch is not found
                $classRecord->branch = [
                    'branchID' => null,
                    'branchDescription' => 'No Branch Assigned'
                ];
            }

            return response()->json($classRecord);
        } else {
            return response()->json(['error' => 'Class record not found'], 404);
        }
    }




    public function getClassRecordGrading($classRecordID)
    {
        $classRecord = ClassRecord::with('grading')->find($classRecordID);

        if (!$classRecord) {
            return response()->json(['error' => 'Class record not found.'], 404);
        }

        $gradingData = Grading::where('classRecordID', $classRecordID)
            ->select('assessmentType', 'term', 'percentage', 'isExamination')
            ->get();

        return response()->json($gradingData);
    }

    public function getSchedule($classRecordID)
    {
        try {
            $schedules = Schedule::where('classRecordID', $classRecordID)
                ->select('scheduleDay', 'scheduleTime')
                ->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGradingDistribution($classRecordID)
    {
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)->get();
        return response()->json($gradingDistributions);
    }




    public function updateClassRecord(Request $request, $classRecordID)
    {
        // Validation rules
        $rules = [
            'schoolYear' => 'required|string',
            'schedules' => 'required|json',
            'semester' => 'required|integer',
            'yearLevel' => 'required|string',
            'template' => 'nullable|string',
            'recordType' => 'required|string',
            'branch' => 'required|integer',
            'programID' => 'required|integer',
            'courseID' => 'required|integer',
            'grading' => 'required|array',
            'grading.*.assessmentType' => 'required|string',
            'grading.*.term' => 'required|in:1,2,3',
            'grading.*.percentage' => 'required|numeric|min:0|max:100',
            'grading.*.isExamination' => 'required|in:true,false,0,1',
            'classImg' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rule for classImg
            'gradingDistributions' => 'required|array',
            'gradingDistributions.*.gradingDistributionType' => 'required|string',
            'gradingDistributions.*.gradingDistributionPercentage' => 'required|numeric|min:0|max:100',
            'gradingDistributions.*.term' => 'required|in:1,2,3',
        ];

        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the class record by ID
        $classRecord = ClassRecord::find($classRecordID);
        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;



        if (!$classRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Class record not found'
            ], 404);
        }

        // Handle the image upload
        if ($request->hasFile('classImg')) {
            $file = $request->file('classImg');
            $fileName = $file->getClientOriginalName(); // Get the original file name
            $directory = 'class_images'; // Target directory inside public
            $filePath = public_path($directory . '/' . $fileName); // Full path

            // Ensure the directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Move the uploaded file to the public/class_images directory
            $file->move(public_path($directory), $fileName);

            // Save the relative path to the database
            $classRecord->classImg = $directory . '/' . $fileName;
        }

        // Update other class record fields and capture old data for audit trail
        $oldClassRecordData = $classRecord->getAttributes(); // Capture old class record data
        $classRecord->update($request->only([
            'schoolYear',
            'semester',
            'yearLevel',
            'template',
            'recordType',
            'programID',
            'courseID',
            'branch'
        ]));
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;
        // Create audit trail for class record update
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'class_record_tbl',
            'new_value' => json_encode($request->only([
                'schoolYear',
                'semester',
                'yearLevel',
                'template',
                'recordType',
                'programID',
                'courseID',
                'branch'
            ])),
            'old_value' => json_encode($oldClassRecordData),
            'description' => "Class record updated successfully {$classRecordDescription}",
            'action_time' => Carbon::now(),
        ]);

        // Process schedules
        $schedules = json_decode($request->input('schedules'), true);
        $existingSchedules = Schedule::where('classRecordID', $classRecordID)->get();

        // Create a map of existing schedules for easy lookup
        $existingScheduleMap = [];
        $oldSchedules = []; // Array to capture old schedules for audit trail
        foreach ($existingSchedules as $schedule) {
            $existingScheduleMap[$schedule->scheduleDay] = $schedule;
        }

        // Arrays to hold descriptions for audit trail
        $scheduleDesc = [];
        $newSchedulesDesc = [];

        // Process new schedules
        foreach ($schedules as $schedule) {
            $day = $schedule['day'];
            $times = $schedule['times'];
            if (isset($existingScheduleMap[$day])) {
                // Day exists, update times
                $existingSchedule = $existingScheduleMap[$day];

                // Capture old data for audit trail
                $oldSchedules[] = $existingSchedule->getAttributes();

                $existingSchedule->scheduleTime = implode(' / ', $times);
                $existingSchedule->save();

                // Create description for updated schedule
                $scheduleDesc[] = "{$day}: {$existingSchedule->scheduleTime} updated to " . implode(' / ', $times);

                // Mark this day as processed
                unset($existingScheduleMap[$day]);
            } else {
                // New day, add all times
                Schedule::create([
                    'scheduleDay' => $day,
                    'scheduleTime' => implode(' / ', $times),
                    'classRecordID' => $classRecordID
                ]);

                // Create description for new schedule
                $newSchedulesDesc[] = "{$day}: " . implode(' / ', $times);
            }
        }

        // Delete schedules for days that are no longer selected and capture their old values
        foreach ($existingScheduleMap as $schedule) {
            $oldSchedules[] = $schedule->getAttributes(); // Capture old data
            $schedule->delete();
        }

        // Create descriptions for deleted schedules
        foreach ($existingScheduleMap as $schedule) {
            $scheduleDesc[] = "{$schedule->scheduleDay}: {$schedule->scheduleTime} deleted";
        }

        // Concatenate the descriptions for the audit trail
        $scheduleDescString = implode(', ', $scheduleDesc);
        $newSchedulesDescString = implode(', ', $newSchedulesDesc);
        $allOldSchedules = json_encode($oldSchedules);

        // Create the audit trail entry for the updated schedules
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'schedules_tbl',
            'new_value' => json_encode($schedules), // Use the whole input
            'old_value' => $allOldSchedules, // Store all old schedules
            'description' => "Schedules updated successfully: {$scheduleDescString}" . ($newSchedulesDescString ? " | New Schedules: {$newSchedulesDescString}" : ""),
            'action_time' => Carbon::now(),
        ]);


        // Update grading distributions and create audit trail
        $gradingDistributionDesc = [];
        $oldDistributions = []; // Array to capture all old distributions
        foreach ($request->input('gradingDistributions', []) as $gradingDistribution) {
            $existingDistribution = GradingDistribution::where('classRecordID', $classRecordID)
                ->where('term', $gradingDistribution['term'])
                ->first();

            if ($existingDistribution) {
                // Capture old data for grading distribution
                $oldDistributions[] = $existingDistribution->getAttributes(); // Store old attributes

                // Update grading distribution
                $existingDistribution->update([
                    'gradingDistributionType' => $gradingDistribution['gradingDistributionType'],
                    'gradingDistributionPercentage' => $gradingDistribution['gradingDistributionPercentage'],
                ]);
                $gradingDistributionDesc[] = "{$gradingDistribution['gradingDistributionType']} ({$gradingDistribution['gradingDistributionPercentage']}%) for Term {$gradingDistribution['term']}";
            } else {
                // Create new grading distribution if it doesn't exist
                GradingDistribution::create([
                    'gradingDistributionType' => $gradingDistribution['gradingDistributionType'],
                    'gradingDistributionPercentage' => $gradingDistribution['gradingDistributionPercentage'],
                    'term' => $gradingDistribution['term'],
                    'classRecordID' => $classRecordID
                ]);

                // Create description for new distribution
                $gradingDistributionDesc[] = "{$gradingDistribution['gradingDistributionType']} ({$gradingDistribution['gradingDistributionPercentage']}%) for Term {$gradingDistribution['term']}";
            }
        }

        // Concatenate the descriptions for the audit trail
        $gradeDisString = implode(', ', $gradingDistributionDesc);

        // Create the audit trail entry
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'grading_distribution_tbl',
            'new_value' => json_encode($request->input('gradingDistributions', [])), // Use the whole input
            'old_value' => json_encode($oldDistributions), // Store all old distributions
            'description' => "Grading Distribution updated successfully in {$classRecordDescription}: {$gradeDisString}",
            'action_time' => Carbon::now(),
        ]);


        // Update the grading information and create audit trail
        $gradingData = $request->input('grading', []);
        $gradeDesc = [];
        $oldGradingData = [];

        foreach ($gradingData as $grading) {
            $existingGrading = Grading::where('classRecordID', $classRecordID)
                ->where('assessmentType', $grading['assessmentType'])
                ->where('term', $grading['term'])
                ->first();

            if ($existingGrading) {
                $oldGradingData[] = [
                    'assessmentType' => $existingGrading->assessmentType,
                    'percentage' => $existingGrading->percentage,
                    'isExamination' => $existingGrading->isExamination
                ];
                $existingGrading->percentage = $grading['percentage'];
                $existingGrading->isExamination = filter_var($grading['isExamination'], FILTER_VALIDATE_BOOLEAN); // Convert to boolean
                $existingGrading->save();
                $gradeDesc[] = "{$grading['assessmentType']} ({$grading['percentage']}%) for Term {$grading['term']}";
            } else {
                if ($grading['percentage'] > 0) {
                    Grading::create([
                        'assessmentType' => $grading['assessmentType'],
                        'term' => $grading['term'],
                        'percentage' => $grading['percentage'],
                        'isExamination' => filter_var($grading['isExamination'], FILTER_VALIDATE_BOOLEAN), // Convert to boolean
                        'classRecordID' => $classRecordID
                    ]);
                }
            }
        }




        $gradeDescString = implode(', ', $gradeDesc);

        // Create a final audit trail entry for the updated grading
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'grading_tbl',
            'new_value' => json_encode($gradingData), // This can store the updated grading data if needed
            'old_value' => json_encode($oldGradingData), // Capture all old values in an array
            'description' => "Grade Percentage Updated for Class Record {$classRecordDescription}: {$gradeDescString}",
            'action_time' => Carbon::now(),
        ]);

        // Return response after processing
        return response()->json([
            'status' => 'success',
            'message' => 'Class record updated successfully',
            'redirect_url' => url('faculty/class-record')
        ]);
    }



    // public function addStudent(Request $request)
    // {
    //     $facultyLoginID = session('loginID');
    //     $selectedClassRecordID = session('selectedClassRecordID');

    //     $facultyRegistration = Registration::where('loginID', $facultyLoginID)->first();

    //     $request->validate([
    //         'classRecordID' => 'required|integer',
    //         'studentNo' => 'required|string|max:255',
    //         'studentFname' => 'required|string|max:255',
    //         'studentLname' => 'required|string|max:255',
    //         'studentMname' => 'nullable|string|max:255',
    //         'Sname' => 'nullable|string|max:255',
    //         'email' => 'required|email|max:255',
    //         'mobileNo' => 'nullable|string|max:255',
    //         'remarks' => 'nullable|string|max:255',
    //     ]);

    //     $data = $request->only([
    //         'classRecordID',
    //         'studentNo',
    //         'studentFname',
    //         'studentLname',
    //         'email',
    //         'mobileNo',
    //         'remarks',
    //         'studentMname',
    //         'Sname'
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $existingLogin = Login::where('email', $data['email'])->first();
    //         if ($existingLogin) {
    //             $existingFaculty = Registration::where('loginID', $existingLogin->loginID)
    //                 ->where('role', 1)
    //                 ->first();

    //             if ($existingFaculty) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'This email is already taken',
    //                 ], 400);
    //             }

    //             $existingAdmin = Admin::where('loginID', $existingLogin->loginID)
    //                 ->first();

    //             if ($existingAdmin) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'This email is already taken',
    //                 ], 400);
    //             }

    //             $existingSuperAdmin = Admin::where('loginID', $existingLogin->loginID)
    //                 ->first();

    //             if ($existingSuperAdmin) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'This email is already taken',
    //                 ], 400);
    //             }


    //             $existingStudent = Student::where('studentNo', $data['studentNo'])
    //                 ->where('classRecordID', $selectedClassRecordID)
    //                 ->first();

    //             if ($existingStudent) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Student already exists for the selected class record!',
    //                 ], 400);
    //             }

    //             $existingEmail = Student::where('email', $data['email'])
    //                 ->where('classRecordID', $selectedClassRecordID)
    //                 ->exists();

    //             if ($existingEmail) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Student email already exists for this class record!',
    //                 ]);
    //             }
    //         } else {
    //             $generatedPassword = bcrypt(Str::random(8));

    //             $login = Login::create([
    //                 'email' => $data['email'],
    //                 'password' => $generatedPassword,
    //             ]);

    //             $loginID = $login->loginID;
    //         }

    //         $existingRegistration = Registration::where('schoolIDNo', $data['studentNo'])->first();

    //         if (!$existingRegistration) {
    //             $adminID = $facultyRegistration->adminID;
    //             $branch = $facultyRegistration->branch;

    //             Registration::create([
    //                 'Lname' => $data['studentLname'],
    //                 'Fname' => $data['studentFname'],
    //                 'Mname' => $data['studentMname'],
    //                 'Sname' => $data['Sname'],
    //                 'role' => 3,
    //                 'schoolIDNo' => $data['studentNo'],
    //                 'branch' => $branch,
    //                 'loginID' => $loginID,
    //                 'adminID' => $adminID,
    //                 'isActive' => 0,
    //                 'isSentCredentials' => 0,
    //             ]);
    //         }

    //         Student::create([
    //             'classRecordID' => $data['classRecordID'],
    //             'studentNo' => $data['studentNo'],
    //             'studentFname' => $data['studentFname'],
    //             'studentLname' => $data['studentLname'],
    //             'email' => $data['email'],
    //             'mobileNo' => $data['mobileNo'],
    //             'remarks' => $data['remarks'],
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Student added successfully!',
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while adding the student: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function addStudent(Request $request)
    {
        $facultyLoginID = session('loginID');
        $selectedClassRecordID = session('selectedClassRecordID');
        $classRecord = ClassRecord::find($selectedClassRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

        $facultyRegistration = Registration::where('loginID', $facultyLoginID)->first();

        $request->validate([
            'classRecordID' => 'required|integer',
            'studentNo' => 'required|string|max:255',
            'studentFname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'studentLname' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'studentMname' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'Sname' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'email' => 'required|email|max:255',
            'mobileNo' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'classRecordID',
            'studentNo',
            'studentFname',
            'studentLname',
            'email',
            'mobileNo',
            'remarks',
            'studentMname',
            'Sname'
        ]);

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        DB::beginTransaction();

        try {
            $existingStudentInCurrentClass = Student::where('studentNo', $data['studentNo'])
                ->where('classRecordID', $selectedClassRecordID)
                ->exists();

            if ($existingStudentInCurrentClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student already exists for the selected class record!',
                ], 400);
            }

            $existingLogin = Login::where('email', $data['email'])->first();

            if ($existingLogin) {
                $existingFaculty = Registration::where('loginID', $existingLogin->loginID)
                    ->where('role', 1)
                    ->exists();

                if ($existingFaculty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already taken by a faculty member.',
                    ], 400);
                }

                $existingAdmin = Admin::where('loginID', $existingLogin->loginID)->exists();
                if ($existingAdmin) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already taken by an admin or super admin.',
                    ], 400);
                }

                $existingStudentEmail = Student::where('email', $data['email'])
                    ->where('classRecordID', $selectedClassRecordID)
                    ->exists();

                if ($existingStudentEmail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student email already exists for this class record!',
                    ], 400);
                }

                // Add the student to the current class
                Student::create([
                    'classRecordID' => $data['classRecordID'],
                    'studentNo' => $data['studentNo'],
                    'studentFname' => $data['studentFname'],
                    'studentLname' => $data['studentLname'],
                    'email' => $data['email'],
                    'mobileNo' => $data['mobileNo'],
                    'remarks' => $data['remarks'],
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Student added to the new class record successfully!',
                ]);
            }

            // Create a new login if it doesn't exist
            $generatedPassword = Str::random(8);

            $login = Login::create([
                'email' => $data['email'],
                'password' => bcrypt($generatedPassword),
            ]);

            $loginID = $login->loginID;

            // Register the student
            $adminID = $facultyRegistration->adminID;
            $branch = $facultyRegistration->branch;

            Registration::create([
                'Lname' => $data['studentLname'],
                'Fname' => $data['studentFname'],
                'Mname' => $data['studentMname'],
                'Sname' => $data['Sname'],
                'role' => 3,
                'schoolIDNo' => $data['studentNo'],
                'branch' => $branch,
                'loginID' => $loginID,
                'adminID' => $adminID,
                'isActive' => 0,
                'isSentCredentials' => 0,
            ]);

            // Add the student to the current class
            Student::create([
                'classRecordID' => $data['classRecordID'],
                'studentNo' => $data['studentNo'],
                'studentFname' => $data['studentFname'],
                'studentLname' => $data['studentLname'],
                'email' => $data['email'],
                'mobileNo' => $data['mobileNo'],
                'remarks' => $data['remarks'],
            ]);

            $registration = Registration::where('loginID', $login->loginID)->first();

            if ($registration) {
                $registration->isSentCredentials = 1;
                $registration->save();
            }

            // Notification::route('mail', $login->email)
            //     ->notify(new FacultySendStudentCredentials($generatedPassword, $request->studentFname, $request->studentLname, $request->studentMname, $request->studentNo));

            Mail::to($login->email)->send(new StudentAccountCredentials(
                $generatedPassword,
                $request->studentFname,
                $request->studentLname,
                $request->studentMname,
                $request->studentNo
            ));

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Add',
                'table_name' => 'student_tbl',
                'new_value' => json_encode($data),
                'description' => "Add Student {$data['studentNo']} in {$classRecordDescription}",
                'action_time' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the student: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkStudentNumberClassRecord(Request $request)
    {
        $request->validate([
            'studentNo' => 'required|string|max:255',
        ]);

        $selectedClassRecordID = session('selectedClassRecordID');

        $existingStudent = Student::where('studentNo', $request->studentNo)->where('classRecordID', $selectedClassRecordID)->exists();

        if ($existingStudent) {
            return response()->json([
                'success' => false,
                'message' => 'Student already exists for this class record!',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function checkEmailStudentClassRecord(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $selectedClassRecordID = session('selectedClassRecordID');

        // Check if the email already exists within the specified class record
        $existingEmail = Student::where('email', $request->email)
            ->where('classRecordID', $selectedClassRecordID)
            ->exists();

        if ($existingEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Student email already exists for this class record!',
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function updateStudent(Request $request)
    {
        $student = Student::findOrFail($request->studentID);
        $currentEmail = $student->email;

        // Define email rules
        $emailRules = ['required', 'email', 'max:255'];

        // Only add unique validation if the email is being changed
        if ($request->email !== $currentEmail) {
            $emailRules[] = Rule::unique('student_tbl')->where(function ($query) use ($request) {
                return $query->where('email', $request->email)
                    ->where('classRecordID', session('selectedClassRecordID'));
            });
        }

        $request->validate([
            'studentID' => 'required|integer',
            'studentNo' => 'required|string|max:255',
            'studentFname' => 'required|string|max:255',
            'studentLname' => 'required|string|max:255',
            'email' => $emailRules,
            'mobileNo' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email is already taken.',
        ]);

        try {
            $oldStudentData = $student->getAttributes();
            $oldEmail = $student->email;

            // Update student data
            $student->update([
                'studentNo' => $request->studentNo,
                'studentFname' => $request->studentFname,
                'studentLname' => $request->studentLname,
                'email' => $request->email,
                'mobileNo' => $request->mobileNo,
                'remarks' => $request->remarks,
            ]);

            // Update email in login_tbl if it has changed
            // if ($oldEmail !== $request->email) {
            //     $loginRecord = Login::where('email', $oldEmail)->first();
            //     if ($loginRecord) {
            //         $loginRecord->update(['email' => $request->email]);
            //     }
            // }

            // Update registration details
            $registrationRecord = Registration::where('schoolIDNo', $request->studentNo)->first();
            if ($registrationRecord) {
                $registrationRecord->update([
                    'Lname' => $request->studentLname,
                    'Fname' => $request->studentFname,
                    'Mname' => $request->studentMname,
                ]);
            }

            // Audit trail for data changes
            $newStudentData = $student->getAttributes();
            if ($oldStudentData !== $newStudentData) {
                $userAdmin = Login::with('registration')
                    ->where('loginID', session('loginID'))
                    ->first();
                $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

                $selectedClassRecordID = session('selectedClassRecordID');
                $classRecord = ClassRecord::find($selectedClassRecordID);
                $programCode = $classRecord->program->programCode ?? 'N/A';
                $yearLevel = $classRecord->yearLevel;
                $courseCode = $classRecord->course->courseCode ?? 'N/A';
                $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

                AuditTrail::create([
                    'record_id' => session('loginID'),
                    'user' => $userName,
                    'action' => 'Update',
                    'table_name' => 'student_tbl',
                    'new_value' => json_encode($newStudentData),
                    'old_value' => json_encode($oldStudentData),
                    'description' => "Updated student {$request->studentNo} in {$classRecordDescription}",
                    'action_time' => Carbon::now(),
                ]);
            }

            return response()->json(['success' => 'Student updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update student information: ' . $e->getMessage()], 500);
        }
    }




    public function importStudent(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $facultyLoginID = session('loginID');
        $facultyRegistration = Registration::where('loginID', $facultyLoginID)->first();

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        $selectedClassRecordID = session('selectedClassRecordID');
        $classRecord = ClassRecord::find($selectedClassRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

        try {
            Excel::import(new StudentImport($classRecordID, $facultyRegistration), $request->file('file'));
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Import',
                'table_name' => 'student_tbl',
                'description' => "Import Student List in {$classRecordDescription}",
                'action_time' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Student information imported successfully']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return response()->json(['status' => 'error', 'message' => implode(' ', $errorMessages)]);
        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function getTermForAssessmentType($assessmentType)
    {
        $formattedAssessmentType = str_replace('-', ' ', $assessmentType);

        $grading = Grading::where('assessmentType', $formattedAssessmentType)
            ->select('term')
            ->first();

        if ($grading) {
            return response()->json(['term' => $grading->term]);
        }

        return response()->json(['term' => null], 404);
    }

    public function getAssessmentTypes($termType)
    {
        // Fetch the assessment types based on the termType (midterm or finals)
        $assessments = Assessment::where('term', $termType)->get(['assessmentType', 'assessmentName']);

        // Return the response as JSON with the relevant assessment types
        return response()->json([
            'assessmentTypes' => $assessments
        ]);
    }


    public function getAlternativeAssessments($termType)
    {
        // Validate termType if necessary
        if (!in_array($termType, ['midterm', 'finals'])) {
            return response()->json(['error' => 'Invalid term type'], 400);
        }

        // Fetch assessments from the database based on the term type
        // Assuming 'term' column in the database represents the term type (e.g., 1 for midterm, 2 for finals)
        $assessments = Assessment::where('term', $termType === 'midterm' ? 1 : 2)
            ->get(['assessmentType', 'assessmentName']); // Adjust fields as needed

        // Return the first available assessment or an empty array if none found
        if ($assessments->isNotEmpty()) {
            return response()->json(['assessments' => $assessments]);
        } else {
            return response()->json(['assessments' => []]);
        }
    }



    public function checkAssessmentTerm($assessmentType)
    {
        $formattedAssessmentType = str_replace('-', ' ', $assessmentType);

        $classRecordID = session('selectedClassRecordID');

        // Check if the assessment type is for midterms
        $isMidterm = Grading::where('assessmentType', $formattedAssessmentType)
            ->where('term', 1) // Ensure we are checking for midterms
            ->where('classRecordID', $classRecordID)
            ->exists();

        // Check if the assessment type is for finals
        $isFinal = Grading::where('assessmentType', $formattedAssessmentType)
            ->where('term', 2) // Ensure we are checking for finals
            ->where('classRecordID', $classRecordID)
            ->exists();

        return response()->json([
            'isMidterm' => $isMidterm,
            'isFinal' => $isFinal
        ]);
    }

    public function storeClassRecordId(Request $request)
    {
        $request->validate([
            'classRecordID' => 'required|string',
        ]);
        session(['selectedClassRecordID' => $request->input('classRecordID')]);
        $redirectUrl = route('faculty.view-class-record-stud-info');
        return redirect($redirectUrl);
    }

    public function storeClassRecordIdNotice(Request $request)
    {

        $notifID = $request->input('notifIDNotice');
        $loginID = session('loginID');

        $user = Login::with('registration')->find($loginID);

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }

        $notification = $user->notifications->find($notifID);

        if ($notification) {
            $notification->markAsRead();

            $notificationData = $notification->data;
            $classRecordID = $notificationData['data']['classRecordID'];

            session(['selectedClassRecordID' => $classRecordID]);

            $redirectUrl = route('faculty.view-class-record-semester-grade');

            return redirect($redirectUrl);
        } else {
            return redirect()->back()->withErrors('Notification not found.');
        }
    }



    public function generatePDF()
    {
        $loginID = session('loginID');
        $registration = Registration::where('loginID', $loginID)->first();
        if (!$registration) {
            // Handle case where no user is found
            abort(404, 'User not found');
        }
        $classRecordID = session('selectedClassRecordID');

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();
        $programCode = $classRecords->program->programCode ?? 'N/A';
        $courseTitle = $classRecords->course->courseTitle ?? 'N/A';
        $yearLevel = $classRecords->yearLevel ?? 'N/A';
        $schoolYear = $classRecords->schoolYear ?? 'N/A';
        $semester = $classRecords->semester ?? 'N/A';


        $assessmentTypesCollection = Grading::where('classRecordID', $classRecordID)
            ->where('term', 1) // Ensure we are only getting assessment types for midterms
            ->distinct()
            ->pluck('assessmentType');
        $assessmentTypes = $assessmentTypesCollection->map(function ($type) {
            return strtolower(trim($type)); // Normalize to lowercase and trim whitespace
        })->toArray();

        // Fetch assessment data
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->where('term', 1) // Midterm assessments
            ->get(['assessmentType', 'percentage'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items, $key) {
                return $items->first(); // Get the percentage for each normalized assessment type
            });

        // Fetch total items data and normalize the case
        $totalItemsData = Assessment::where('classRecordID', $classRecordID)
            ->where('term', 1) // Term 1
            ->get(['assessmentType', 'totalItem'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->sum('totalItem') ?? 0;
            })
            ->toArray(); // Convert to array

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0); // Default to 0
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData);
        $studentScores = [];
        foreach ($assessmentTypes as $type) {
            $assessmentIDs = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 1)
                ->pluck('assessmentID');


            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $classRecordID)
                ->selectRaw('studentID, SUM(score) as totalScore')
                ->groupBy('studentID')
                ->pluck('totalScore', 'studentID');

            // Calculate the final score for each student
            $finalScores = [];
            foreach ($scores as $studentID => $totalScore) {
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $assessmentData[$type]->percentage ?? 0;
                $finalScores[$studentID] = ($totalScore / $totalItem) * $percentage;
            }

            $studentScores[$type] = $finalScores;
        }
        $totalPercentage = $assessmentData->sum('percentage');

        $pdf = PDF::loadView('faculty.midterm.faculty-class-record-info-pdf', [
            'classRecords' => $classRecords,
            'programCode' => $programCode,
            'courseTitle' => $courseTitle,
            'yearLevel' => $yearLevel,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'assessmentData' => $assessmentData,
            'totalItemsData' => $totalItemsData,
            'assessmentTypes' => $assessmentTypes,
            'studentScores' => $studentScores,
            'totalPercentage' => $totalPercentage,
            'registration' => $registration,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('midterm_grades.pdf');
    }

    public function generateFinalPDF()
    {
        $loginID = session('loginID');
        $registration = Registration::where('loginID', $loginID)->first();
        if (!$registration) {
            // Handle case where no user is found
            abort(404, 'User not found');
        }
        $classRecordID = session('selectedClassRecordID');

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();
        $programCode = $classRecords->program->programCode ?? 'N/A';
        $courseTitle = $classRecords->course->courseTitle ?? 'N/A';
        $yearLevel = $classRecords->yearLevel ?? 'N/A';
        $schoolYear = $classRecords->schoolYear ?? 'N/A';
        $semester = $classRecords->semester ?? 'N/A';

        $assessmentTypesCollection = Grading::where('classRecordID', $classRecordID)
            ->where('term', 2) // Finals term
            ->distinct()
            ->pluck('assessmentType');
        $assessmentTypes = $assessmentTypesCollection->map(function ($type) {
            return strtolower(trim($type)); // Normalize to lowercase and trim whitespace
        })->toArray();

        // Fetch assessment data
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->where('term', 2) // Finals term
            ->get(['assessmentType', 'percentage'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->first(); // Get the percentage for each normalized assessment type
            });

        // Fetch total items data and normalize the case
        $totalItemsData = Assessment::where('classRecordID', $classRecordID)
            ->where('term', 2) // Finals term
            ->get(['assessmentType', 'totalItem'])
            ->groupBy(function ($item) {
                return strtolower(trim($item->assessmentType)); // Normalize to lowercase
            })
            ->map(function ($items) {
                return $items->sum('totalItem') ?? 0;
            })
            ->toArray();

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0); // Default to 0
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData);

        $totalPercentage = array_sum(array_column($assessmentData->toArray(), 'percentage'));

        $studentScores = [];
        foreach ($assessmentTypes as $type) {
            $assessmentIDs = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 2) // Filter only for finals (term 2)
                ->pluck('assessmentID');

            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $classRecordID)
                ->selectRaw('studentID, SUM(score) as totalScore')
                ->groupBy('studentID')
                ->pluck('totalScore', 'studentID');

            // Calculate the final score for each student
            $finalScores = [];
            foreach ($scores as $studentID => $totalScore) {
                $totalItem = $combinedTotalItems[$type] ?? 0; // Avoid division by zero
                $percentage = $assessmentData[$type]->percentage ?? 0;

                if ($totalItem > 0) {
                    // Only calculate score if totalItem is greater than zero
                    $finalScores[$studentID] = ($totalScore / $totalItem) * $percentage;
                } else {
                    // Set score to zero if totalItem is zero
                    $finalScores[$studentID] = 0;
                }
            }

            $studentScores[$type] = $finalScores;
        }
        // dd($studentScores);
        $pdf = PDF::loadView('faculty.finals.faculty-class-record-info-pdf', [
            'classRecords' => $classRecords,
            'programCode' => $programCode,
            'courseTitle' => $courseTitle,
            'yearLevel' => $yearLevel,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'assessmentData' => $assessmentData,
            'totalItemsData' => $totalItemsData,
            'assessmentTypes' => $assessmentTypes,
            'studentScores' => $studentScores,
            'totalPercentage' => $totalPercentage,
            'registration' => $registration,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('final_grades.pdf');
    }



    public function generateSemestralPDF(Request $request)
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID');

        $registration = Registration::where('loginID', $loginID)->first();
        if (!$registration) {
            // Handle case where no user is found
            abort(404, 'User not found');
        }

        $admin = Admin::find($registration->adminID);
        if (!$admin) {
            abort(404, 'Admin not found');
        }

        $classRecordID = session('selectedClassRecordID');

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();
        $programCode = $classRecords->program->programCode ?? 'N/A';
        $courseTitle = $classRecords->course->courseTitle ?? 'N/A';
        $yearLevel = $classRecords->yearLevel ?? 'N/A';
        $schoolYear = $classRecords->schoolYear ?? 'N/A';
        $semester = $classRecords->semester ?? 'N/A';

        // Fetch class record with students
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();

        // Raw SQL to calculate midterm and final grades
        $gradingDistribution = GradingDistribution::where('classRecordID', $classRecordID)
            ->first();

        if ($gradingDistribution) {
            $midtermPercentage = $gradingDistribution->midtermPercentage ?? 0;
            $finalPercentage = $gradingDistribution->finalPercentage ?? 0;
        } else {
            $midtermPercentage = 0;
            $finalPercentage = 0;
        }

        // Compute grades
        $grades = DB::table('student_assessment_tbl AS sa')
            ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
            ->join('grading_tbl AS g', function ($join) use ($classRecordID) {
                $join->on('a.assessmentType', '=', 'g.assessmentType')
                    ->on('a.term', '=', 'g.term')
                    ->where('g.classRecordID', '=', $classRecordID);
            })
            ->select(
                'sa.studentID',
                'a.term',
                DB::raw('SUM(sa.score) / SUM(a.totalItem) * 100 * MAX(g.percentage) / 100 AS assessmentGrade')
            )
            ->whereIn('a.term', [1, 2]) // Midterm (1) and Final (2)
            ->groupBy('sa.studentID', 'a.term', 'a.assessmentType')
            ->get()
            ->groupBy('studentID')
            ->map(function ($grades, $studentID) use ($midtermPercentage, $finalPercentage) {
                $midtermGrade = $grades->where('term', 1)->sum('assessmentGrade');
                $finalGrade = $grades->where('term', 2)->sum('assessmentGrade');

                $midtermResult = $midtermGrade * ($midtermPercentage / 100);
                $finalResult = $finalGrade * ($finalPercentage / 100);

                $semestralGrade = $midtermResult + $finalResult;
                $pointGrade = $this->convertToPointGrade($semestralGrade);
                list($gwa, $remarks) = $this->convertToGWAAndRemarks($pointGrade);
                return [
                    'midtermGrade' => number_format($midtermGrade, 2),
                    'finalGrade' => number_format($finalGrade, 2),
                    'semestralGrade' => number_format($semestralGrade, 2),
                    'pointGrade' => $pointGrade,
                    'gwa' => $gwa,
                    'remarks' => $remarks,
                ];
            });
        $submittedFile = SubmittedFile::where('classRecordID', $classRecordID)->first();
        $displaySignature = $submittedFile && $submittedFile->status == 1;

        // Pass computed grades to the PDF view
        $pdf = Pdf::loadView('faculty.faculty-class-record-pdf-semester-grade', [
            'classRecords' => $classRecords,
            'grades' => $grades,
            'programCode' => $programCode,
            'courseTitle' => $courseTitle,
            'yearLevel' => $yearLevel,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'registration' => $registration,
            'admin' => $admin,
            'displaySignature' => $displaySignature,
        ]);

        // Set PDF orientation to landscape
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('semestral_report.pdf');
    }

    public function sendNotificationToAdminSubmittedGrades($classRecordID, $fileID)
    {
        $professorID = session('loginID');
        $type = 'submit_grades';

        if (is_null($classRecordID)) {
            return response()->json(['message' => 'Invalid class record ID.'], 400);
        }

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
            })
            ->where('loginID', $professorID)
            ->first();

        $admin = Login::where('loginID', $user->adminID)->first();

        $adminInfo = Admin::where('loginID', $user->adminID)->first();



        if (!$user || !$user->login) {
            return response()->json(['message' => 'Invalid professor or login information.'], 400);
        }

        $professorSalutation = $user->salutation;
        $professorLname = $user->Lname;
        $professorFname = $user->Fname;

        $adminSalutation = $adminInfo->salutation;
        $adminLname = $adminInfo->Lname;
        $adminFname = $adminInfo->Fname;

        $classRecord = ClassRecord::find($classRecordID);
        $courseTitle = $classRecord ? $classRecord->course->courseTitle : 'Unknown Course';


        // dd($admin);

        if ($admin) {
            $admin->notify(new FacultySendSemesterGrades($type, $professorSalutation, $professorLname, $professorFname, $courseTitle, $classRecordID, $fileID));
        }

        Mail::to($admin->email)->send(new SubmitClassRecordReportEmail(
            $type,
            $professorSalutation,
            $professorLname,
            $professorFname,
            $adminSalutation,
            $adminLname,
            $adminFname,
            $courseTitle,
        ));


        return response()->json(['message' => 'Notification sent successfully.']);
    }


    public function generateAndSubmitGradesPDF()
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID');

        $registration = Registration::where('loginID', $loginID)->first();
        if (!$registration) {
            // Handle case where no user is found
            abort(404, 'User not found');
        }

        $admin = Admin::find($registration->adminID);
        if (!$admin) {
            abort(404, 'Admin not found');
        }

        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->where('isArchived', 0)
            ->firstOrFail();

        $programCode = $classRecords->program->programCode ?? 'N/A';
        $courseTitle = $classRecords->course->courseTitle ?? 'N/A';
        $courseCode = $classRecords->course->courseCode ?? 'N/A';
        $yearLevel = $classRecords->yearLevel ?? 'N/A';
        $schoolYear = $classRecords->schoolYear ?? 'N/A';
        $semester = $classRecords->semester ?? 'N/A';
        $createdAt = $classRecords->created_at->format('Ymd');

        // Raw SQL to calculate midterm and final grades
        $gradingDistribution = GradingDistribution::where('classRecordID', $classRecordID)->first();
        $midtermPercentage = $gradingDistribution->midtermPercentage ?? 0;
        $finalPercentage = $gradingDistribution->finalPercentage ?? 0;

        // Compute grades
        $grades = DB::table('student_assessment_tbl AS sa')
            ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
            ->join('grading_tbl AS g', function ($join) use ($classRecordID) {
                $join->on('a.assessmentType', '=', 'g.assessmentType')
                    ->on('a.term', '=', 'g.term')
                    ->where('g.classRecordID', '=', $classRecordID);
            })
            ->select(
                'sa.studentID',
                'a.term',
                DB::raw('SUM(sa.score) / SUM(a.totalItem) * 100 * MAX(g.percentage) / 100 AS assessmentGrade')
            )
            ->whereIn('a.term', [1, 2]) // Midterm (1) and Final (2)
            ->groupBy('sa.studentID', 'a.term', 'a.assessmentType')
            ->get()
            ->groupBy('studentID')
            ->map(function ($grades, $studentID) use ($midtermPercentage, $finalPercentage) {
                $midtermGrade = $grades->where('term', 1)->sum('assessmentGrade');
                $finalGrade = $grades->where('term', 2)->sum('assessmentGrade');

                $midtermResult = $midtermGrade * ($midtermPercentage / 100);
                $finalResult = $finalGrade * ($finalPercentage / 100);

                $semestralGrade = $midtermResult + $finalResult;
                $pointGrade = $this->convertToPointGrade($semestralGrade);
                list($gwa, $remarks) = $this->convertToGWAAndRemarks($pointGrade);
                return [
                    'midtermGrade' => number_format($midtermGrade, 2),
                    'finalGrade' => number_format($finalGrade, 2),
                    'semestralGrade' => number_format($semestralGrade, 2),
                    'pointGrade' => $pointGrade,
                    'gwa' => $gwa,
                    'remarks' => $remarks,
                ];
            });

        $submittedFile = SubmittedFile::create([
            'file' => "{$createdAt}-{$programCode}_{$courseCode}_semestral_grades.pdf",
            // 'status' => 0,
            'classRecordID' => $classRecordID,
        ]);

        $displaySignature = $submittedFile && $submittedFile->status == 1;

        // Pass computed grades to the PDF view
        $pdf = Pdf::loadView('faculty.faculty-class-record-pdf-semester-grade', [
            'classRecords' => $classRecords,
            'grades' => $grades,
            'programCode' => $programCode,
            'courseTitle' => $courseTitle,
            'yearLevel' => $yearLevel,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'registration' => $registration,
            'admin' => $admin,
            'displaySignature' => $displaySignature,
        ]);

        // Set PDF orientation to landscape
        $pdf->setPaper('a4', 'landscape');

        // $pdfPath = "{$createdAt}-{$programCode}_{$courseCode}_semestral_grades.pdf";
        // Storage::disk('public')->put($pdfPath, $pdf->output());

        $pdfPath = "{$createdAt}-{$programCode}_{$courseCode}_semestral_grades.pdf";
        $storagePath = public_path('grade_files/' . $pdfPath);

        // Check if the directory exists and create it if necessary
        if (!file_exists(public_path('grade_files'))) {
            mkdir(public_path('grade_files'), 0755, true);
        }

        // Save the PDF to the defined path in the public directory
        file_put_contents($storagePath, $pdf->output());

        SubmittedFile::where('classRecordID', $classRecordID)->update(['file' => $pdfPath, 'status' => 0]);

        ClassRecord::where('classRecordID', $classRecordID)->update(['isSubmitted' => 1]);

        // Send notification to admin
        $this->sendNotificationToAdminSubmittedGrades($classRecordID, $submittedFile->fileID);

        return response()->json([
            'success' => true,
            'message' => 'Files have been submitted and notification sent successfully.',
        ]);
    }

    public function generateAndSubmitGradesExcel()
    {
        $classRecordID = session('selectedClassRecordID');

        $classRecord = ClassRecord::with(['program', 'course'])->find($classRecordID);

        if (!$classRecord) {
            return redirect()->back()->withErrors(['error' => 'Class Record not found']);
        }

        $programCode = $classRecord->program->programCode;
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode;

        $semester = match ($classRecord->semester) {
            1 => '1st Semester',
            2 => '2nd Semester',
            3 => 'Summer Semester',
            default => 'Unknown Semester',
        };

        $schoolYear = $classRecord->schoolYear;
        $filename = "{$programCode}-{$yearLevel}-{$courseCode}-{$semester}-{$schoolYear}-ClassRecord.xlsx";

        $storagePath = 'grade_files/' . $filename;


        Excel::store(new SemesterGradeExport($classRecordID), $storagePath, 'public');

        $submittedFile = SubmittedFile::create([
            'file' => $filename,
            'classRecordID' => $classRecordID,
        ]);

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Submit Class Record Report',
            'table_name' => 'class_record_tbl',
            'description' => "Download ClassRecord: " . $filename,
            'action_time' => Carbon::now(),
        ]);

        SubmittedFile::where('classRecordID', $classRecordID)->update(['file' => $filename, 'status' => 1]);

        ClassRecord::where('classRecordID', $classRecordID)->update(['isSubmitted' => 1]);

        $this->sendNotificationToAdminSubmittedGrades($classRecordID, $submittedFile->fileID);


        return response()->json([
            'success' => true,
            'message' => 'Excel file has been submitted and notification sent successfully.',
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

    public function storeEsignature(Request $request)
    {
        // Define validation rules
        $rules = [
            'esign' => 'required|image|mimes:png|max:2048',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules);

        // Initialize the image path
        $imagePath = null;

        if ($request->hasFile('esign')) {
            $file = $request->file('esign');

            // Generate a unique file name
            $fileName = $file->getClientOriginalName();

            // Define the path where the image will be saved
            $directory = 'esignatures';
            $fullPath = public_path($directory . '/' . $fileName);

            // Ensure the directory exists
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Get file content and save it using file_put_contents
            $fileContent = file_get_contents($file->getPathname());
            file_put_contents($fullPath, $fileContent);

            $imagePath = $directory . '/' . $fileName;
        }
        // Retrieve the loginID from the session
        $loginID = session('loginID');

        // Find the user in registration_tbl by loginID
        $user = Registration::where('loginID', $loginID)->first();

        // If the user is found, update their e-signature path
        if ($user) {
            $user->update([
                'signature' => $imagePath, // Assuming 'eSignature' is the column name for storing the image path
            ]);

            // return redirect()->back()->with('success', 'E-signature saved successfully!');
            return response()->json([
                'success' => true,
                'message' => 'E-signature saved successfully!',
            ]);
        }

        // If no user is found, return an error response
        return redirect()->back()->with('error', 'User not found!');
    }

    private function removeWhiteBackgroundGD($inputPath, $outputPath)
    {
        $image = imagecreatefrompng($inputPath);
        $width = imagesx($image);
        $height = imagesy($image);

        // Create a new true color image with alpha channel support
        $newImage = imagecreatetruecolor($width, $height);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefill($newImage, 0, 0, $transparent);

        // Replace white background with transparent
        $white = imagecolorallocate($image, 255, 255, 255);
        imagecolortransparent($image, $white);

        // Copy the old image to the new image
        imagecopy($newImage, $image, 0, 0, 0, 0, $width, $height);

        // Save the new image
        imagepng($newImage, $outputPath);

        // Free memory
        imagedestroy($image);
        imagedestroy($newImage);
    }

    public function sendStudentCredentials(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'studentno' => 'required|max:255',
            'email' => 'required|email|max:255',

        ]);

        $login = Login::where('email', $request->email)->first();

        if (!$login) {
            return response()->json(['success' => false, 'message' => 'Email not found.']);
        }

        $plainPassword = Str::random(8);

        $hashedPassword = Hash::make($plainPassword);
        $login->password = $hashedPassword;
        $login->save();

        $registration = Registration::where('loginID', $login->loginID)->first();

        if ($registration) {
            $registration->isSentCredentials = 1;
            $registration->save();
        }

        Notification::route('mail', $request->email)
            ->notify(new FacultySendStudentCredentials($plainPassword, $request->fname, $request->lname, $request->mname, $request->studentno));

        return response()->json(['success' => true, 'message' => 'Student credentials sent successfully.']);
    }

    public function showGradingDistribution()
    {
        $storedAssessmentType = session('assessmentType');

        $storedgradingDistributionType = session('selectedTab');


        // dd($storedgradingDistributionType);

        // If no assessment type is found in the session, handle it
        if (!$storedAssessmentType) {
            return redirect()->back()->with('error', 'Assessment type not found');
        }

        // $gradingDistribution = GradingDistribution::where('gradingDistributionType', $gradingDistributionType)->first();

        // If grading distribution not found, handle it
        // if (!$gradingDistribution) {
        //     return redirect()->back()->with('error', 'Grading distribution not found');
        // }

        $storedgradingDistributionType = strtolower(str_replace(' ', '-', $storedgradingDistributionType));

        $gradingDistributionType = str_replace(' ', ' ', $storedgradingDistributionType);

        $redirectUrl = route('faculty.view-class-record-info', [
            'gradingDistributionType' => $gradingDistributionType,
            'assessmentType' => $storedAssessmentType
        ]);

        // Log the redirect URL for debugging
        // \Log::info('Redirecting to: ' . $redirectUrl);

        return redirect($redirectUrl);
    }


    public function storeTermInSession(Request $request)
    {
        $term = $request->input('term');

        // Store the term in the session
        session(['gradingTerm' => $term]);

        return response()->json(['success' => true]);
    }

    public function checkAssessmentTermNew($assessmentType)
    {
        $formattedAssessmentType = str_replace('-', ' ', $assessmentType);

        $classRecordID = session('selectedClassRecordID');

        return response()->json([
            'formattedAssessmentType' => $formattedAssessmentType,
        ]);
    }



    public function redirectToLists(Request $request)
    {
        // Validate the request to ensure 'assessmentID' is provided and is an integer

        $gradingDistributionType = $request->input('gradingDistributionType');

        // Convert the assessment type to a slug format for use in the URL
        $gradingDistributionType = strtolower(str_replace(' ', '-', $gradingDistributionType));

        $redirectUrl = route('faculty.grading-distribution', ['gradingDistributionType' => $gradingDistributionType]);


        // Redirect the user
        return redirect($redirectUrl);
    }

    public function redirectToGrades(Request $request)
    {
        // Validate the request to ensure 'assessmentID' is provided and is an integer

        $gradingDistributionType1 = $request->input('gradingDistributionType1');

        // Convert the assessment type to a slug format for use in the URL
        $gradingDistributionType1 = strtolower(str_replace('-', ' ', $gradingDistributionType1));

        // dd($gradingDistributionType1);

        $redirectUrl = route('faculty.view-class-record-stud-grade', ['gradingDistributionType' => $gradingDistributionType1]);
        // Redirect the user
        return redirect($redirectUrl);
    }



    public function exportSemesterGradeToExcel(Request $request)
    {
        $classRecordID = session('selectedClassRecordID');

        // Fetch the ClassRecord to get necessary attributes for the filename
        $classRecord = ClassRecord::with(['program', 'course'])->find($classRecordID);

        if (!$classRecord) {
            return redirect()->back()->withErrors(['error' => 'Class Record not found']);
        }

        // Construct the filename based on your requirements
        $programCode = $classRecord->program->programCode; // Assuming this is correct
        $yearLevel = $classRecord->yearLevel; // Make sure this attribute exists
        $courseCode = $classRecord->course->courseCode; // Assuming this is correct

        // Convert semester numeric value to string representation
        $semester = match ($classRecord->semester) {
            1 => '1st Semester',
            2 => '2nd Semester',
            3 => 'Summer Semester',
            default => 'Unknown Semester', // Fallback if needed
        };

        $schoolYear = $classRecord->schoolYear; // Assuming this is correct

        // Constructing the filename
        $filename = "{$programCode}-{$yearLevel}-{$courseCode}-{$semester}-{$schoolYear}-ClassRecord.xlsx";
        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Export',
            'table_name' => 'class_record_tbl',
            'description' => "Download ClassRecord: " . $filename,
            'action_time' => Carbon::now(),
        ]);

        return Excel::download(new SemesterGradeExport($classRecordID, $filename), $filename);
    }

    public function sendBatchStudentCredentials(Request $request)
    {
        $selectedStudIDs = $request->input('selectedStudIDs');

        if (is_null($selectedStudIDs) || !is_array($selectedStudIDs)) {
            return response()->json(['message' => 'Invalid student IDs.'], 400);
        }

        $students = Student::whereIn('studentID', $selectedStudIDs)->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No students found with the provided IDs.'], 404);
        }

        foreach ($students as $student) {
            $login = Login::where('email', $student->email)->first();

            if ($login) {
                $plainPassword = Str::random(8);
                $hashedPassword = Hash::make($plainPassword);

                $login->password = $hashedPassword;
                $login->save();

                $registration = Registration::where('loginID', $login->loginID)->first();
                if ($registration) {
                    $registration->isSentCredentials = 1;
                    $registration->save();
                }

                $login->notify(new BatchFacultySendStudentCredentials(
                    $plainPassword,
                    $student->studentNo,
                    $student->studentFname,
                    $student->studentLname,
                    $student->studentMname,
                    $login->email
                ));
            }
        }

        return response()->json(['success' => true, 'message' => 'Batch credentials sent successfully.']);
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

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'registration',
            'description' => "User ". $userName." change password",
            'action_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
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
            // Update personal information
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

    public function markAsRead(Request $request)
    {
        $feedbackIds = $request->input('feedback_ids');

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        if ($feedbackIds) {
            DB::table('feedback_tbl')
                ->whereIn('feedbackID', $feedbackIds)
                ->update(['read_at' => now()]);

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => "Marked as read",
                'table_name' => 'feedback_tbl',
                'new_value' => '',
                'description' => "Feedback marked as read",
                'action_time' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Feedback marked as read.']);
        }

        return response()->json(['message' => 'No feedback IDs provided.'], 400);
    }

    public function deleteFeedback(Request $request)
    {
        $feedbackIds = $request->input('feedback_ids');

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        if ($feedbackIds) {
            DB::table('feedback_tbl')
                ->whereIn('feedbackID', $feedbackIds)
                ->update(['deleted_at' => now()]);

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => "Deleted",
                'table_name' => 'feedback_tbl',
                'new_value' => '',
                'description' => "Feedback deleted",
                'action_time' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Feedback deleted successfully.']);
        }

        return response()->json(['message' => 'No feedback IDs provided.'], 400);
    }

    public function displayFacultyArchivedClassRecord()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
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
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        return view('faculty.faculty-archived-record', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount', 'unreadCountFeedback'));
    }


    public function displayFacultyActivityLog()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Registration::with(['login'])
            ->whereHas('login', function ($query) {
                $query->where('role', 1);
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
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();

        return view('faculty.faculty-act-log', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount', 'unreadCountFeedback'));
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

    // public function archiveClassRecord(Request $request)
    // {
    //     $classRecordID = $request->classRecordID;

    //     $classRecord = ClassRecord::find($classRecordID);

    //     if ($classRecord) {
    //         $classRecord->isArchived = 1;
    //         $classRecord->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Class record archived successfully.',
    //             'courseTitle' => $classRecord->courseTitle,
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Class record not found.',
    //     ], 404);
    // }

    public function archiveClassRecord(Request $request)
    {
        $classRecordID = $request->classRecordID;

        $classRecord = ClassRecord::find($classRecordID);

        if (!$classRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Class record not found.',
            ], 404);
        }

        $unpublishedAssessments = Assessment::where('classRecordID', $classRecordID)
            ->where('isPublished', 0)
            ->exists();

        if ($unpublishedAssessments) {
            return response()->json([
                'success' => false,
                'message' => 'Publish the scores of all students before archiving this class record.',
            ], 400);
        }

        $classRecord->isArchived = 1;
        $classRecord->save();

        $classRecordID = session('selectedClassRecordID');

        // Fetch the ClassRecord to get necessary attributes for the filename
        $classRecord = ClassRecord::with(['program', 'course'])->find($classRecordID);

        if (!$classRecord) {
            return redirect()->back()->withErrors(['error' => 'Class Record not found']);
        }

        // Construct the filename based on your requirements
        $programCode = $classRecord->program->programCode; // Assuming this is correct
        $yearLevel = $classRecord->yearLevel; // Make sure this attribute exists
        $courseCode = $classRecord->course->courseCode; // Assuming this is correct

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Archived',
            'table_name' => 'class_record_tbl',
            'new_value' => '',
            'description' => "Class Record Archived Successfully: " . $classRecordDescription,
            'action_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Class record archived successfully.',
            'courseTitle' => $classRecord->courseTitle,
        ]);
    }


    // public function getFacultyArchives(Request $request)
    // {
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);

    //     $loginID = session('loginID');
    //     $registration = Registration::where('loginID', $loginID)->first();

    //     $schoolYear = null;
    //     $semester = null;

    //     if ($registration) {
    //         $admin = Admin::where('branch', $registration->branch)->first();

    //         if ($admin) {
    //             $schoolYear = $admin->schoolYear;
    //             $semester = $admin->semester;
    //         }
    //     }

    //     $total = ClassRecord::where('isArchived', 0)->where('loginID', $loginID)->count();
    //     $classRecords = ClassRecord::with(['course.program', 'login.registration', 'branchDetail'])
    //         ->where('isArchived', 1)
    //         ->where('loginID', $loginID)
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

    public function getFacultyArchives(Request $request)
    {
        $loginID = session('loginID');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');
        $orderColumn = $request->input('order.0.column', '6'); // Default column: created_at
        $orderDirection = $request->input('order.0.dir', 'desc');

        // Define columns for ordering
        $columns = ['courseTitle', 'courseCode', 'programName', 'branch', 'schoolYear', 'yearLevel', 'created_at'];
        $orderColumnName = $columns[$orderColumn] ?? 'created_at';

        // Get school year and semester
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

        // Build query
        $query = ClassRecord::with(['course.program', 'branchDetail'])
            ->where('isArchived', 1)
            ->where('loginID', $loginID);

        // Search filter
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->whereHas('course', function ($query) use ($searchValue) {
                    $query->where('courseTitle', 'like', "%{$searchValue}%")
                        ->orWhere('courseCode', 'like', "%{$searchValue}%");
                })->orWhereHas('course.program', function ($query) use ($searchValue) {
                    $query->where('programCode', 'like', "%{$searchValue}%");
                })->orWhereHas('branchDetail', function ($query) use ($searchValue) {
                    $query->where('branchDescription', 'like', "%{$searchValue}%");
                })->orWhere('schoolYear', 'like', "%{$searchValue}%")
                    ->orWhere('yearLevel', 'like', "%{$searchValue}%")
                    ->orWhere('created_at', 'like', "%{$searchValue}%");
            });
        }

        // Get total filtered records
        $totalFiltered = $query->count();

        // Fetch data with ordering and pagination
        $classRecords = $query
            ->orderByRaw("CASE WHEN schoolYear = ? AND semester = ? THEN 0 ELSE 1 END", [$schoolYear, $semester])
            ->orderBy($orderColumnName, $orderDirection)
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

        // Return JSON response
        return response()->json([
            'data' => $classRecords,
            'recordsTotal' => ClassRecord::where('isArchived', 1)->where('loginID', $loginID)->count(),
            'recordsFiltered' => $totalFiltered,
        ]);
    }
    public function showIndividualReport($studentID)
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID'); // Retrieve the classRecordID from the session
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->firstOrFail();
        $selectedStudent = Student::findOrFail($studentID);

        // Fetch the specific student by ID for the class record
        $student = Student::whereHas('classrecord', function ($query) use ($classRecordID) {
            $query->where('classRecordID', $classRecordID);
        })->where('studentID', $selectedStudent->studentID)->firstOrFail();

        // Fetch distinct assessment types for all terms (1 and 2)
        $assessmentTypes = Grading::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->distinct()
            ->pluck('assessmentType')
            ->map(fn($type) => strtolower(trim($type))) // Normalize assessment types
            ->toArray();

        // Fetch assessment titles and total items from Assessment table for all terms
        $assessmentTitles = Assessment::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->get(['assessmentID', 'assessmentType', 'assessmentName', 'totalItem', 'assessmentDate', 'term', 'passingItem']); // Include assessmentID and assessmentDate

        // Fetch grading distribution types for term names
        $gradingDistributionTypes = GradingDistribution::where('classRecordID', $classRecordID)
            ->pluck('gradingDistributionType', 'term')
            ->toArray();

        // Organize assessment titles by assessmentType and term, and sum total items
        $organizedAssessmentTitles = [];
        $totalItemsData = [];
        foreach ($assessmentTitles as $assessment) {
            $type = strtolower(trim($assessment->assessmentType));
            $term = $assessment->term;

            if (!isset($organizedAssessmentTitles[$type])) {
                $organizedAssessmentTitles[$type] = [];
            }
            if (!isset($organizedAssessmentTitles[$type][$term])) {
                $organizedAssessmentTitles[$type][$term] = [];
            }
            $organizedAssessmentTitles[$type][$term][] = $assessment->assessmentName;

            // Sum total items for each assessment type per term
            if (!isset($totalItemsData[$type])) {
                $totalItemsData[$type] = [];
            }
            $totalItemsData[$type][$term] = ($totalItemsData[$type][$term] ?? 0) + $assessment->totalItem;
        }

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, []);
        foreach ($combinedTotalItems as $type => &$termData) {
            $termData = array_fill_keys([1, 2], 0); // Default keys for terms
            if (isset($totalItemsData[$type])) {
                foreach ($totalItemsData[$type] as $key => $value) {
                    $termData[$key] = $value; // Populate data only if it exists
                }
            }
        }

        // Fetch percentages for each assessment type for all terms
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->get(['assessmentType', 'term', 'percentage'])
            ->groupBy(fn($item) => strtolower(trim($item->assessmentType)));

        // Initialize student scores
        $studentScores = [];

        // Fetch the student scores for each assessment type and term
        foreach ($assessmentTypes as $type) {
            $gradingInfo = $assessmentData->get($type);

            // Fetch assessment IDs for the specific assessment type and terms
            $assessmentIDsByTerm = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->whereIn('term', [1, 2, 3])
                ->get()
                ->groupBy('term');

            foreach ([1, 2, 3] as $term) {
                if (!isset($assessmentIDsByTerm[$term])) {
                    continue; // Skip terms with no assessments
                }

                $assessmentIDs = $assessmentIDsByTerm[$term]->pluck('assessmentID') ?? collect();

                // Fetch scores for the specific student based on assessment IDs and term
                $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                    ->where('classRecordID', $classRecordID)
                    ->where('studentID', $selectedStudent->studentID) // Fetch scores only for the selected student
                    ->get();

                foreach ($scores as $score) {
                    // Initialize student score data if not set
                    if (!isset($studentScores[$selectedStudent->studentID])) {
                        $studentScores[$selectedStudent->studentID] = [
                            'rawScores' => [],
                            'finalScores' => [],
                        ];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['rawScores'][$term])) {
                        $studentScores[$selectedStudent->studentID]['rawScores'][$term] = [];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['rawScores'][$term][$type])) {
                        $studentScores[$selectedStudent->studentID]['rawScores'][$term][$type] = [];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['finalScores'][$term])) {
                        $studentScores[$selectedStudent->studentID]['finalScores'][$term] = [];
                    }

                    // Fetch assessment details based on assessmentID
                    $assessmentDetails = $assessmentTitles->firstWhere('assessmentID', $score->assessmentID);
                    $assessmentName = $assessmentDetails->assessmentName ?? 'Unknown';
                    $totalItem = $assessmentDetails->totalItem ?? 1; // Avoid division by zero
                    $assessmentDate = $assessmentDetails->assessmentDate ?? 'Unknown';
                    $passingItem = $assessmentDetails->passingItem ?? 1; // Avoid division by zero

                    // Store the raw score for this assessment type and term
                    $studentScores[$selectedStudent->studentID]['rawScores'][$term][$type][] = [
                        'score' => $score->score,
                        'assessmentName' => $assessmentName,
                        'passingItem' => $passingItem,
                        'totalItem' => $totalItem,
                        'assessmentDate' => $assessmentDate,
                    ];

                    // Calculate final score for this assessment type and term

                    $totalItem = $combinedTotalItems[$type][$term] ?? 1; // Avoid division by zero
                    $percentage = $gradingInfo?->firstWhere('term', $term)?->percentage ?? 0;

                    if ($totalItem > 0 && $percentage > 0) {
                        $finalScore = ($score->score / $totalItem) * $percentage; // Calculate weighted score
                        $studentScores[$selectedStudent->studentID]['finalScores'][$term][$type] =
                            ($studentScores[$selectedStudent->studentID]['finalScores'][$term][$type] ?? 0) + $finalScore;
                    }
                }
            }
        }

        // dd($studentScores);

        // Notifications and feedbacks
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
                return $notification;
            });

        $feedbacks = Feedback::where('loginID', $loginID)->with('student')->get();
        $unreadCount = $notifications->whereNull('read_at')->count();
        $unreadCountFeedback = $feedbacks->whereNull('read_at')->whereNull('deleted_at')->count();
        $gradingDistributions = GradingDistribution::where('classRecordID', $classRecordID)
            ->get();

        return view('faculty.faculty-class-record-individual-reports', [
            'loginID' => $loginID,
            'classRecordID' => $classRecordID,
            'student' => $student,
            'studentScores' => $studentScores,
            'assessmentTitles' => $organizedAssessmentTitles,
            'combinedTotalItems' => $combinedTotalItems,
            'assessmentData' => $assessmentData,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'unreadCountFeedback' => $unreadCountFeedback,
            'classRecords' => $classRecords,
            'gradingDistributions' => $gradingDistributions,
            'gradingDistributionTypes' => $gradingDistributionTypes,
        ]);
    }



    public function generateIndividualReport($studentID)
    {
        $loginID = session('loginID');
        $classRecordID = session('selectedClassRecordID'); // Retrieve the classRecordID from the session
        $classRecords = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $classRecordID)
            ->firstOrFail();
        $selectedStudent = Student::findOrFail($studentID);

        // Fetch the specific student by ID for the class record
        $student = Student::whereHas('classrecord', function ($query) use ($classRecordID) {
            $query->where('classRecordID', $classRecordID);
        })->where('studentID', $selectedStudent->studentID)->firstOrFail();

        // Fetch distinct assessment types for all terms (1 and 2)
        $assessmentTypes = Grading::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->distinct()
            ->pluck('assessmentType')
            ->map(fn($type) => strtolower(trim($type))) // Normalize assessment types
            ->toArray();

        // Fetch assessment titles and total items from Assessment table for all terms
        $assessmentTitles = Assessment::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->get(['assessmentID', 'assessmentType', 'assessmentName', 'totalItem', 'assessmentDate', 'term', 'passingItem']); // Include assessmentID and assessmentDate

        // Fetch grading distribution types for term names
        $gradingDistributionTypes = GradingDistribution::where('classRecordID', $classRecordID)
            ->pluck('gradingDistributionType', 'term')
            ->toArray();

        // Organize assessment titles by assessmentType and term, and sum total items
        $organizedAssessmentTitles = [];
        $totalItemsData = [];
        foreach ($assessmentTitles as $assessment) {
            $type = strtolower(trim($assessment->assessmentType));
            $term = $assessment->term;

            if (!isset($organizedAssessmentTitles[$type])) {
                $organizedAssessmentTitles[$type] = [];
            }
            if (!isset($organizedAssessmentTitles[$type][$term])) {
                $organizedAssessmentTitles[$type][$term] = [];
            }
            $organizedAssessmentTitles[$type][$term][] = $assessment->assessmentName;

            // Sum total items for each assessment type per term
            if (!isset($totalItemsData[$type])) {
                $totalItemsData[$type] = [];
            }
            $totalItemsData[$type][$term] = ($totalItemsData[$type][$term] ?? 0) + $assessment->totalItem;
        }

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, []);
        foreach ($combinedTotalItems as $type => &$termData) {
            $termData = array_fill_keys([1, 2], 0); // Default keys for terms
            if (isset($totalItemsData[$type])) {
                foreach ($totalItemsData[$type] as $key => $value) {
                    $termData[$key] = $value; // Populate data only if it exists
                }
            }
        }

        // Fetch percentages for each assessment type for all terms
        $assessmentData = Grading::where('classRecordID', $classRecordID)
            ->whereIn('term', [1, 2, 3]) // Include all terms
            ->get(['assessmentType', 'term', 'percentage'])
            ->groupBy(fn($item) => strtolower(trim($item->assessmentType)));

        // Initialize student scores
        $studentScores = [];

        // Fetch the student scores for each assessment type and term
        foreach ($assessmentTypes as $type) {
            $gradingInfo = $assessmentData->get($type);

            // Fetch assessment IDs for the specific assessment type and terms
            $assessmentIDsByTerm = Assessment::where('classRecordID', $classRecordID)
                ->where('assessmentType', $type)
                ->whereIn('term', [1, 2, 3])
                ->get()
                ->groupBy('term');

            foreach ([1, 2, 3] as $term) {
                if (!isset($assessmentIDsByTerm[$term])) {
                    continue; // Skip terms with no assessments
                }

                $assessmentIDs = $assessmentIDsByTerm[$term]->pluck('assessmentID') ?? collect();

                // Fetch scores for the specific student based on assessment IDs and term
                $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                    ->where('classRecordID', $classRecordID)
                    ->where('studentID', $selectedStudent->studentID) // Fetch scores only for the selected student
                    ->get();

                foreach ($scores as $score) {
                    // Initialize student score data if not set
                    if (!isset($studentScores[$selectedStudent->studentID])) {
                        $studentScores[$selectedStudent->studentID] = [
                            'rawScores' => [],
                            'finalScores' => [],
                        ];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['rawScores'][$term])) {
                        $studentScores[$selectedStudent->studentID]['rawScores'][$term] = [];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['rawScores'][$term][$type])) {
                        $studentScores[$selectedStudent->studentID]['rawScores'][$term][$type] = [];
                    }

                    if (!isset($studentScores[$selectedStudent->studentID]['finalScores'][$term])) {
                        $studentScores[$selectedStudent->studentID]['finalScores'][$term] = [];
                    }

                    // Fetch assessment details based on assessmentID
                    $assessmentDetails = $assessmentTitles->firstWhere('assessmentID', $score->assessmentID);
                    $assessmentName = $assessmentDetails->assessmentName ?? 'Unknown';
                    $totalItem = $assessmentDetails->totalItem ?? 1; // Avoid division by zero
                    $assessmentDate = $assessmentDetails->assessmentDate ?? 'Unknown';
                    $passingItem = $assessmentDetails->passingItem ?? 1; // Avoid division by zero

                    // Store the raw score for this assessment type and term
                    $studentScores[$selectedStudent->studentID]['rawScores'][$term][$type][] = [
                        'score' => $score->score,
                        'assessmentName' => $assessmentName,
                        'passingItem' => $passingItem,
                        'totalItem' => $totalItem,
                        'assessmentDate' => $assessmentDate,
                    ];

                    // Calculate final score for this assessment type and term
                    $totalItem = $combinedTotalItems[$type][$term] ?? 1; // Avoid division by zero
                    $percentage = $gradingInfo?->firstWhere('term', $term)?->percentage ?? 0;

                    if ($totalItem > 0 && $percentage > 0) {
                        $finalScore = ($score->score / $totalItem) * $percentage; // Calculate weighted score
                        $studentScores[$selectedStudent->studentID]['finalScores'][$term][$type] =
                            ($studentScores[$selectedStudent->studentID]['finalScores'][$term][$type] ?? 0) + $finalScore;
                    }
                }
            }
        }

        // Generate PDF
        $pdf = Pdf::loadView('faculty.faculty-class-record-pdf-individual-reports', [
            'student' => $student,
            'studentScores' => $studentScores,
            'assessmentTitles' => $organizedAssessmentTitles,
            'combinedTotalItems' => $combinedTotalItems,
            'gradingDistributionTypes' => $gradingDistributionTypes,
            'classRecords' => $classRecords,
            'assessmentData' => $assessmentData
        ]);

        // Download the PDF
        return $pdf->download('Individual_Report_' . strtoupper($student->studentLname) . '.pdf');
    }


    public function toggleGrades(Request $request)
    {
        $request->validate([
            'term' => 'required|integer',
            'classRecordID' => 'required|integer',
            'isPublished' => 'required|boolean',
        ]);

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        if ($request->isPublished == 1) {
            $unpublishedAssessments = Assessment::where('classRecordID', $request->classRecordID)
                ->where('isPublished', 0)
                ->where('term', $request->term)
                ->exists();

            if ($unpublishedAssessments) {
                return response()->json([
                    'success' => false,
                    'message' => 'Publish all assessments before publishing the grades.',
                ], 400);
            }
        }

        $updated = GradingDistribution::where('term', $request->term)
            ->where('classRecordID', $request->classRecordID)
            ->update(['isPublished' => $request->isPublished]);

        $type = GradingDistribution::where('term', $request->term)
            ->where('classRecordID', $request->classRecordID)
            ->pluck('gradingDistributionType')
            ->implode(', ');

        if ($updated) {
            $actionType = $request->isPublished ? 'Published' : 'Unpublished';

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => $actionType,
                'table_name' => 'gradingDistribution_tbl',
                'new_value' => '',
                'description' => "$actionType $type grades",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->isPublished ? 'Grades published successfully.' : 'Grades unpublished successfully.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update grades.'], 500);
    }
}
