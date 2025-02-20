<?php

namespace App\Http\Controllers;

use App\Imports\CourseImport;
use App\Imports\ProfessorImport;
use App\Imports\ProgramImport;
use App\Mail\FacultyAccountCredentials;
use App\Mail\SendEmailNotificationFacultyLoads;
use App\Models\Admin;
use App\Models\ClassRecord;
use App\Models\Courses;
use App\Models\GradingDistribution;
use App\Models\Login;
use App\Models\Programs;
use App\Models\Registration;
use App\Models\Student;
use App\Models\SubmittedFile;
use App\Notifications\AdminSendFacultyCredentials;
use App\Notifications\AdminValidateGradesFile;
use App\Notifications\BatchAdminSendFacultyCredentials;
use App\Notifications\SubmitClassRecordNotice;
use Carbon\Carbon;
use App\Models\AuditTrail;
use App\Notifications\EmailNotificationAdminIntegration;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use setasign\Fpdi\Fpdi as FpdiFpdi;
use setasign\Fpdi\Tfpdf\Fpdi;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{

    public function adminDashboardPage()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        if ($userinfo && $userinfo->branch) {
            $professors = Registration::whereHas('admin')
                ->select('Fname', 'Lname', 'loginID')
                ->where('branch', $userinfo->branch)
                ->where('adminID', $userinfo->branch)
                ->where('role', 1)
                ->get();
        } else {
            $professors = collect();
        }

        return view('admin.admin-home', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'professors'));
    }

    public function getClassRecordsDashboard(Request $request, $professorId)
    {
        $courseId = $request->query('courseId');
        $academicYear = $request->query('academicYear');
        $semester = $request->query('semester');
        $programId = $request->query('programId');

        // Build the query with optional filters
        $query = ClassRecord::where('loginID', $professorId);

        if ($courseId) {
            $query->where('courseID', $courseId);
        }
        if ($academicYear) {
            $query->where('schoolYear', $academicYear);
        }
        if ($semester) {
            $query->where('semester', $semester);
        }
        if ($programId) {
            $query->where('programID', $programId);
        }

        $classRecords = $query->get();

        $data = [];

        $data['classRecords'] = $classRecords->map(function ($record) {
            // Count the number of students for each class record
            $totalStudents = Student::where('classRecordID', $record->classRecordID)->count();

            // Get grading distribution for midterm and final percentages
            $gradingDistribution = GradingDistribution::where('classRecordID', $record->classRecordID)->first();
            $midtermPercentage = $gradingDistribution->midtermPercentage ?? 0;
            $finalPercentage = $gradingDistribution->finalPercentage ?? 0;

            // Compute grades and remarks (Passed/Failed)
            $grades = DB::table('student_assessment_tbl AS sa')
                ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
                ->join('grading_tbl AS g', function ($join) use ($record) {
                    $join->on('a.assessmentType', '=', 'g.assessmentType')
                        ->on('a.term', '=', 'g.term')
                        ->where('g.classRecordID', '=', $record->classRecordID);
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
                ->map(function ($grades) use ($midtermPercentage, $finalPercentage) {
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

            $passedCount = $grades->filter(function ($grade) {
                return $grade['remarks'] === 'Passed';
            })->count();

            $failedCount = $grades->filter(function ($grade) {
                return $grade['remarks'] === 'Failed';
            })->count();

            return [
                'classRecordID' => $record->classRecordID,
                'courseId' => $record->course->courseID,
                'courseName' => $record->course->courseTitle,
                'academicYear' => $record->schoolYear,
                'semester' => $record->semester,
                'programId' => $record->program->programID,
                'programName' => $record->program->programTitle,
                'totalStudents' => $totalStudents,
                'passed' => $passedCount,
                'failed' => $failedCount
            ];
        });

        return response()->json($data);
    }





    public function getClassRecordsByCourseAndSemester(Request $request)
    {
        $courseId = $request->query('courseId');
        $professorId = $request->query('professorId');

        $classRecords = ClassRecord::where('courseID', $courseId)
            ->where('loginID', $professorId)
            ->get(['schoolYear', 'semester']);

        return response()->json(['classRecords' => $classRecords]);
    }


    public function getClassRecordsByCourseSemesterSchoolYear(Request $request)
    {
        $courseId = $request->query('courseId');
        $semester = $request->query('semester');
        $schoolYear = $request->query('schoolYear');
        $professorId = $request->query('professorId');

        $classRecords = ClassRecord::where('courseID', $courseId)
            ->where('loginID', $professorId)
            ->where('semester', $semester)
            ->where('schoolYear', $schoolYear)
            ->with('program')
            ->get([
                'schoolYear',
                'semester',
                'programID',
            ]);

        $formattedRecords = $classRecords->map(function ($record) {
            return [
                'schoolYear' => $record->schoolYear,
                'semester' => $record->semester,
                'programId' => $record->program->programID ?? null,
                'programName' => $record->program->programTitle ?? null,
            ];
        });

        return response()->json(['classRecords' => $formattedRecords]);
    }


    public function adminAccountsPage()
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('admin.admin-accounts', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    // public function getFacultyAccData(Request $request)
    // {
    //     $branch = session('branch');
    //     $loginID = session('loginID');


    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);

    //     $total = Registration::count();
    //     $registrations = Registration::with('login')
    //         ->offset($start)
    //         ->limit($length)
    //         ->where('role', 1)
    //         ->where('branch', $branch)
    //         ->where('adminID', $loginID)
    //         ->get()
    //         ->map(function ($admin) {
    //             return [
    //                 'id' => $admin->adminID,
    //                 'Fname' => $admin->Fname,
    //                 'Lname' => $admin->Lname,
    //                 'schoolIDNo' => $admin->schoolIDNo,
    //                 'email' => $admin->login->email ?? 'N/A',
    //                 'status' => $admin->isActive ? 'Active' : 'Inactive',
    //                 'isSentCredentials' => $admin->isSentCredentials,
    //                 'salutation' => $admin->salutation,
    //                 'Mname' => $admin->Mname,
    //                 'Sname' => $admin->Sname,
    //                 'branch' => $admin->branchDetail ? $admin->branchDetail->branchDescription : 'N/A',
    //             ];
    //         });

    //     return response()->json([
    //         'data' => $registrations,
    //         'recordsTotal' => $total,
    //         'recordsFiltered' => $total,
    //     ]);
    // }

    public function getFacultyAccData(Request $request)
    {
        $branch = session('branch');
        $loginID = session('loginID');

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            null,
            'schoolIDNo',
            'Lname',
            'Fname',
            'Mname',
            'Sname',
            'login.email',
            'isActive',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'Lname';

        $registrations = Registration::with(['login'])
            ->where('role', 1)
            ->where('branch', $branch)
            ->where('adminID', $loginID)
            ->get();

        if (!empty($searchValue)) {
            $registrations = $registrations->filter(function ($admin) use ($searchValue) {
                return stripos(strtolower($admin->schoolIDNo), $searchValue) !== false ||
                    stripos(strtolower($admin->Lname), $searchValue) !== false ||
                    stripos(strtolower($admin->Fname), $searchValue) !== false ||
                    stripos(strtolower($admin->login->email ?? ''), $searchValue) !== false;
            });
        }

        $registrations = $registrations->sortBy(function ($admin) use ($orderColumnName) {
            return strtolower(data_get($admin, $orderColumnName, ''));
        }, SORT_REGULAR, $orderDirection === 'desc');

        $total = $registrations->count();

        $registrationsData = $registrations
            ->slice($start, $length)
            ->map(function ($admin) {
                return [
                    'id' => $admin->registrationID,
                    'Fname' => $admin->Fname,
                    'Lname' => $admin->Lname,
                    'schoolIDNo' => $admin->schoolIDNo,
                    'email' => $admin->login->email ?? 'N/A',
                    'status' => $admin->isActive ? 'Active' : 'Inactive',
                    'isSentCredentials' => $admin->isSentCredentials,
                    'salutation' => $admin->salutation,
                    'Mname' => $admin->Mname,
                    'Sname' => $admin->Sname,
                    'branch' => $admin->branchDetail ? $admin->branchDetail->branchDescription : 'N/A',
                ];
            });

        return response()->json([
            'data' => $registrationsData->values(),
            'recordsTotal' => Registration::where('role', 1)->where('branch', $branch)->where('adminID', $loginID)->count(),
            'recordsFiltered' => $total,
        ]);
    }





    public function addProfessor(Request $request)
    {
        $adminLoginID = session('loginID');
        $branch = session('branch');

        // Validate request data
        $request->validate([
            'Lname' => 'required|string|max:255',
            'Fname' => 'required|string|max:255',
            'schoolIDNo' => 'nullable|string|max:255',
            'email' => 'required|max:255|unique:login_tbl,email',
            'salutation' => 'required|string|max:255',
        ]);

        $data = $request->only(['Lname', 'Fname', 'Mname', 'Sname', 'schoolIDNo', 'email', 'salutation']);

        // Create login data for the professor
        // $login = new Login();
        // $login->email = $data['email'];
        // $login->password = Hash::make('password123');
        // $login->save();

        $login = new Login();
        $login->email = $data['email'];
        $plainPassword = Str::random(8); // Generate a random password
        $login->password = bcrypt($plainPassword);
        $login->save();

        $loginID = $login->loginID;

        // Create registration data for the professor
        $professor = new Registration();
        $professor->Lname = $data['Lname'];
        $professor->Fname = $data['Fname'];
        $professor->Mname = $data['Mname'];
        $professor->Sname = $data['Sname'];
        $professor->role = 1;
        $professor->branch = $branch;
        $professor->isActive = 0;
        $professor->isSentCredentials = 0;
        $professor->schoolIDNo = $data['schoolIDNo'];
        $professor->salutation = $data['salutation'];
        $professor->loginID = $loginID;
        $professor->adminID = $adminLoginID;
        $professor->save();

        // Capture the new professor and login data for the audit trail
        $profName = $professor->Lname . ', ' . $professor->Fname;

        $newValues = json_encode([
            'registration' => $professor->getAttributes(),
            'login' => $login->getAttributes(),
        ]);

        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        // Create audit trail entry
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Create',
            'table_name' => 'registration_tbl, login_tbl',
            'new_value' => $newValues,
            'description' => "Professor added successfully: $profName",
            'action_time' => Carbon::now(),
        ]);

        $prof = Registration::where('loginID', $loginID)->first();
        if ($prof) {
            $prof->isSentCredentials = 1;
            $prof->save();
        }

        // Notification::route('mail', $login->email)
        //     ->notify(new AdminSendFacultyCredentials($plainPassword, $professor->Fname, $professor->Lname, $professor->salutation, $login->email));

        Mail::to($login->email)->send(new FacultyAccountCredentials($plainPassword, $professor->Fname, $professor->Lname, $professor->salutation, $login->email));

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Professor added successfully!',
        ]);
    }

    public function updateProfessor(Request $request)
    {

        $professor = Registration::find($request->input('registrationID'));
        if (!$professor) {
            return response()->json([
                'success' => false,
                'message' => 'Professor not found.',
            ], 404);
        }

        $request->validate([
            'Lname' => 'required|string|max:255',
            'Fname' => 'required|string|max:255',
            'schoolIDNo' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:login_tbl,email,' . $professor->loginID . ',loginID', // Exclude the current user's loginID
            'salutation' => 'required|string|max:255',
        ]);

        // Find the associated login record by loginID
        $login = Login::find($professor->loginID);

        if (!$login) {
            return response()->json([
                'success' => false,
                'message' => 'Login record not found.',
            ], 404);
        }

        // Capture old values for audit trail
        $oldValues = json_encode([
            'registration' => $professor->getOriginal(),
            'login' => $login->getOriginal(),
        ]);

        // Update the login table (email)
        $login->email = $request->email;
        $login->save();

        // Update the professor's data
        $professor->Lname = $request->Lname;
        $professor->Fname = $request->Fname;
        $professor->Mname = $request->Mname;
        $professor->Sname = $request->Sname;
        $professor->schoolIDNo = $request->schoolIDNo;
        $professor->salutation = $request->salutation;
        $professor->save();

        // Capture new values for audit trail
        $newValues = json_encode([
            'registration' => $professor->getAttributes(),
            'login' => $login->getAttributes(),
        ]);

        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        // Create audit trail entry
        $userProf = $professor->Lname . ', ' . $professor->Fname;
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'registration_tbl, login_tbl',
            'old_value' => $oldValues,
            'new_value' => $newValues,
            'description' => "Professor updated: $userProf",
            'action_time' => Carbon::now(),
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Professor updated successfully!',
        ]);
    }





    public function sendFacultyCredentials(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // 'salutation' => 'string|max:255',

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
            ->notify(new AdminSendFacultyCredentials($plainPassword, $request->fname, $request->lname, $request->salutation, $request->email));

        return response()->json(['success' => true, 'message' => 'Credentials sent successfully.']);
    }

    public function importProfessor(Request $request)
    {

        $branch = session('branch');
        $adminLoginID = session('loginID');
        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();


        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;
        // $adminID = $userAdmin->admin->adminID;

        // dd($adminLoginID);
        Excel::import(new ProfessorImport($adminLoginID, $branch), $request->file('file'));


        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Import',
            'table_name' => 'registration_tbl, login_tbl',
            'description' => "Import Professor List",
            'action_time' => Carbon::now(),
        ]);


        return  response()->json(['success' => true, 'message' => 'Professors information imported successfully.']);
    }

    public function importProgram(Request $request)
    {
        $branch = session('branch');

        Excel::import(new ProgramImport($branch), $request->file('file'));
        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Import',
            'table_name' => 'program_tbl',
            'description' => "Import Program List",
            'action_time' => Carbon::now(),
        ]);
        return  response()->json(['success' => true, 'message' => 'Programs imported successfully.']);
    }




    public function importCourses(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'programID' => 'required|exists:program_tbl,programID',
        ]);

        $programID = $request->input('programID');

        Excel::import(new CourseImport($programID), $request->file('file'));

        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Import',
            'table_name' => 'course_tbl',
            'description' => "Import Course List",
            'action_time' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Courses imported successfully']);
    }



    public function displayAdminCourseList()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $programs = Programs::byBranch()->get();


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

        return view('admin.admin-courselist', compact('loginID', 'userinfo', 'user', 'role', 'programs', 'notifications', 'unreadCount'));
    }

    public function displayAdminProgramList()
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('admin.admin-programlist', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount'));
    }

    // public function getProgramData(Request $request)
    // {
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);

    //     $total = Programs::count();
    //     $programs = Programs::byBranch()
    //         ->offset($start)
    //         ->limit($length)
    //         ->get();

    //     return response()->json([
    //         'data' => $programs,
    //         'recordsTotal' => $total,
    //         'recordsFiltered' => $total,
    //     ]);
    // }

    public function getProgramData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            'programCode',
            'programTitle',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'programTitle';

        $programsQuery = Programs::byBranch();

        if (!empty($searchValue)) {
            $programsQuery = $programsQuery->where(function ($query) use ($searchValue) {
                $query->whereRaw('LOWER(programCode) LIKE ?', ["%$searchValue%"])
                    ->orWhereRaw('LOWER(programTitle) LIKE ?', ["%$searchValue%"]);
            });
        }

        $total = $programsQuery->count();

        $programs = $programsQuery
            ->orderBy($orderColumnName, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(function ($program) {
                return [
                    'programID' => $program->programID,
                    'programCode' => $program->programCode,
                    'programTitle' => $program->programTitle,
                ];
            });

        return response()->json([
            'data' => $programs,
            'recordsTotal' => Programs::byBranch()->count(),
            'recordsFiltered' => $total,
        ]);
    }


    // public function getCourseData(Request $request)
    // {
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);
    //     $courses = [];

    //     $programs = Programs::with('courses')
    //         ->byBranch()
    //         ->get();

    //     $totalCourses = $programs->reduce(function ($count, $program) {
    //         return $count + $program->courses->count();
    //     }, 0);

    //     $allCourses = $programs->flatMap(function ($program) {
    //         return $program->courses->map(function ($course) use ($program) {
    //             return [
    //                 'courseID' => $course->courseID,
    //                 'courseCode' => $course->courseCode,
    //                 'courseTitle' => $course->courseTitle,
    //                 'programCode' => $program->programCode,
    //                 'programID' => $program->programID,
    //             ];
    //         });
    //     });

    //     $paginatedCourses = $allCourses->slice($start, $length)->values();

    //     return response()->json([
    //         'data' => $paginatedCourses->all(),
    //         'recordsTotal' => $totalCourses,
    //         'recordsFiltered' => $totalCourses,
    //     ]);
    // }

    public function getCourseData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            'courseCode',
            'courseTitle',
            'programCode',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'courseTitle';

        $programs = Programs::with('courses')
            ->byBranch()
            ->get();

        $allCourses = $programs->flatMap(function ($program) {
            return $program->courses->map(function ($course) use ($program) {
                return [
                    'courseID' => $course->courseID,
                    'courseCode' => $course->courseCode,
                    'courseTitle' => $course->courseTitle,
                    'programCode' => $program->programCode,
                    'programID' => $program->programID,
                ];
            });
        });

        if (!empty($searchValue)) {
            $allCourses = $allCourses->filter(function ($course) use ($searchValue) {
                return stripos(strtolower($course['courseCode']), $searchValue) !== false ||
                    stripos(strtolower($course['courseTitle']), $searchValue) !== false ||
                    stripos(strtolower($course['programCode']), $searchValue) !== false;
            });
        }

        $allCourses = $allCourses->sortBy(function ($course) use ($orderColumnName) {
            return strtolower($course[$orderColumnName] ?? '');
        }, SORT_REGULAR, $orderDirection === 'desc');

        $totalCourses = $allCourses->count();

        $paginatedCourses = $allCourses->slice($start, $length)->values();

        return response()->json([
            'data' => $paginatedCourses->all(),
            'recordsTotal' => $programs->reduce(function ($count, $program) {
                return $count + $program->courses->count();
            }, 0),
            'recordsFiltered' => $totalCourses,
        ]);
    }




    public function displayAdminActivityLog()
    {
        $loginID = session('loginID');
        $role = session('role');


        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('admin.admin-act-log', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount'));
    }

    public function getActLogsData(Request $request)
    {
        // $loginID = session('loginID');

        // $start = $request->input('start', 0);
        // $length = $request->input('length', 10);

        // $total = AuditTrail::count();
        // $logs = AuditTrail::where('record_id', $loginID)
        //     ->offset($start)
        //     ->limit($length)
        //     ->get()
        //     ->map(function ($log) {
        //         return [
        //             'id' => $log->record_id,
        //             'user' => $log->user,
        //             'action' => $log->action,
        //             'table_name' => $log->table_name,
        //             'old_value' => $log->old_value,
        //             'new_value' => $log->new_value,
        //             'description' => $log->description,
        //             'action_time' => $log->action_time,
        //         ];
        //     });

        // return response()->json([
        //     'data' => $logs,
        //     'recordsTotal' => $total,
        //     'recordsFiltered' => $total,
        // ]);

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








    public function showToVerifyReports()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // Get the submitted files with related class record, login, program, and course data
        $submittedFiles = SubmittedFile::with([
            'classrecord.login.registration',       // Get professor (Login model)
            'classrecord.program',     // Get program (Programs model)
            'classrecord.course'       // Get course (Courses model)
        ])->where('status', 0)->get();


        // You can now access professor name, programTitle, and courseTitle for each submitted file
        $submittedData = $submittedFiles->map(function ($file) {
            $professor = $file->classrecord->login->registration;
            return [
                'fileID' => $file->fileID,
                'file' => $file->file,
                'status' => $file->status,
                'classRecordID' => $file->classRecordID,
                'createdAt' => $file->created_at,
                'professorName' => ($professor ? ($professor->Fname . ' ' . $professor->Lname) : 'N/A'),
                'programTitle' => $file->classrecord->program->programTitle ?? 'N/A',
                'courseTitle' => $file->classrecord->course->courseTitle ?? 'N/A',
            ];
        });

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

        return view('admin.admin-to-verify-reports', compact('loginID', 'role', 'user', 'userinfo', 'submittedData', 'notifications', 'unreadCount'));
    }

    public function storeReportId(Request $request)
    {
        // Validate the request
        $request->validate([
            'fileID' => 'required|string',
        ]);

        session(['selectedReportID' => $request->input('fileID')]);

        // Return a JSON response with redirect URL
        return response()->json([
            'status' => 'success',
            'redirect_url' => route('admin.view-to-verify-report'),
        ]);
    }

    public function storeReportIdNotif(Request $request)
    {

        session(['selectedReportID' => $request->input('fileIDNotif')]);

        $notifID = $request->input('notifIDAdmin');

        $loginID = session('loginID');
        $user = Login::with('registration')->find($loginID);

        if (!$user) {
            return redirect()->back()->withErrors('User not found.');
        }

        $notification = $user->notifications->find($notifID);

        if ($notification) {
            // Update the read_at timestamp
            $notification->markAsRead();

            // Optionally, handle additional logic here
            // $redirectUrl = route('admin.view-to-verify-report');

            $redirectUrl = route('admin.class-record-report');



            // Redirect to the specified URL
            return redirect($redirectUrl)->with('success', 'Notification marked as read.');
        } else {
            // Handle the case where the notification is not found
            return redirect()->back()->withErrors('Notification not found.');
        }
    }


    public function viewSubmittedFile()
    {

        $loginID = session('loginID');
        $role = session('role');
        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;


        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();
        // Retrieve the fileID from the session
        $fileID = session('selectedReportID');

        // dd($fileID);

        if (!$fileID) {
            // Handle the case where the fileID is not set in the session
            return redirect()->route('admin.admin-view-file')->withErrors('No file ID found in session.');
        }

        // Retrieve the submission based on fileID
        $submission = SubmittedFile::find($fileID);

        // dd($submission);

        if (!$submission) {
            // Handle the case where the submission is not found
            return redirect()->route('admin.admin-view-file')->withErrors('File not found.');
        }

        // Pass the submission data to the view
        return view('admin.admin-view-file', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'submission'));
    }

    public function showClassRecordReports()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $adminBranch = $user->branch ?? null;

        // $classRecordsQuery = ClassRecord::with(['course.program', 'login.registration'])
        //     ->where('isArchived', 0)
        //     ->where('branch', $adminBranch)
        //     ->latest('created_at');

        // $classRecords = $classRecordsQuery->get();


        // $submittedFiles = SubmittedFile::with([
        //     'classrecord.login.registration',
        //     'classrecord.program',
        //     'classrecord.course'
        // ])->get()->keyBy('classRecordID');

        // $submittedData = $classRecords->map(function ($record) use ($submittedFiles) {
        //     $professor = $record->login->registration ?? null;
        //     $fileDetails = $submittedFiles->has($record->classRecordID)
        //         ? SubmittedFile::find($submittedFiles[$record->classRecordID]->fileID)
        //         : null;

        //     return [
        //         'classRecordID' => $record->classRecordID,
        //         'professorName' => ($professor ? ($professor->salutation . ' ' . $professor->Fname . ' ' . $professor->Lname) : 'N/A'),
        //         'programTitle' => $record->course->program->programTitle ?? 'N/A',
        //         'courseTitle' => $record->course->courseTitle ?? 'N/A',
        //         'status' => $fileDetails ? 'Submitted' : 'Unsubmitted',
        //         'fileID' => $fileDetails->fileID ?? null,
        //         'fileName' => $fileDetails->file ?? null,
        //         'profID' => $record->login->loginID,
        //         'updatedAt' => $record->updated_at,
        //     ];
        // });


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

        return view('admin.admin-class-record-lists', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'adminBranch'));
    }

    public function getClassRecordReports(Request $request)
    {
        $loginID = session('loginID');
        $user = Admin::with(['login'])->where('loginID', $loginID)->first();
        $adminBranch = $user->branch ?? null;

        // DataTable parameters
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');
        $orderColumn = $request->input('order.0.column', '2');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = ['professorName', 'programTitle', 'courseTitle', 'status', 'updatedAt'];
        $orderColumnName = $columns[$orderColumn] ?? 'updatedAt';

        $query = ClassRecord::with(['course.program', 'login.registration'])
            ->join('course_tbl', 'class_record_tbl.courseID', '=', 'course_tbl.courseID')
            ->join('program_tbl', 'course_tbl.programID', '=', 'program_tbl.programID')
            ->select('class_record_tbl.*', 'program_tbl.programTitle as programTitle', 'course_tbl.courseTitle as courseTitle')
            ->where('class_record_tbl.isArchived', 0)
            ->where('class_record_tbl.branch', $adminBranch);

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('login.registration', function ($subQuery) use ($searchValue) {
                    $subQuery->where('Fname', 'like', "%{$searchValue}%")
                        ->orWhere('Lname', 'like', "%{$searchValue}%");
                })->orWhereHas('course.program', function ($subQuery) use ($searchValue) {
                    $subQuery->where('programTitle', 'like', "%{$searchValue}%");
                })->orWhereHas('course', function ($subQuery) use ($searchValue) {
                    $subQuery->where('courseTitle', 'like', "%{$searchValue}%");
                });
            });
        }

        $totalFiltered = $query->count();

        $classRecords = $query->orderBy($orderColumnName, $orderDirection)
            ->offset($start)
            ->limit($length)
            ->get();

        $submittedFiles = SubmittedFile::with(['classrecord.login.registration', 'classrecord.program', 'classrecord.course'])
            ->get()
            ->keyBy('classRecordID');

        $logs = $classRecords->map(function ($record) use ($submittedFiles) {
            $professor = $record->login->registration ?? null;
            $fileDetails = $submittedFiles->has($record->classRecordID)
                ? SubmittedFile::find($submittedFiles[$record->classRecordID]->fileID)
                : null;

            return [
                'classRecordID' => $record->classRecordID,
                'professorName' => $professor ? $professor->salutation . ' ' . $professor->Fname . ' ' . $professor->Lname : 'N/A',
                'programTitle' => $record->course->program->programTitle ?? 'N/A',
                'courseTitle' => $record->course->courseTitle ?? 'N/A',
                'status' => $fileDetails ? 'Submitted' : 'Unsubmitted',
                'fileID' => $fileDetails->fileID ?? null,
                'fileName' => $fileDetails->file ?? null,
                'profID' => $record->login->loginID,
                'updatedAt' => $record->updated_at,
            ];
        });

        return response()->json([
            'data' => $logs,
            'recordsTotal' => ClassRecord::where('isArchived', 0)->where('branch', $adminBranch)->count(),
            'recordsFiltered' => $totalFiltered,
        ]);
    }



    public function showVerifiedReports()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;


        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // Get the submitted files with status = 1 (verified reports)
        $submittedFiles = SubmittedFile::with([
            'classrecord.login.registration',       // Get professor (Login model)
            'classrecord.program',     // Get program (Programs model)
            'classrecord.course'       // Get course (Courses model)
        ])->where('status', 1)->get();  // Only get verified reports

        // Map the data to include professor name, programTitle, and courseTitle for each verified report
        $submittedData = $submittedFiles->map(function ($file) {
            $professor = $file->classrecord->login->registration;
            return [
                'fileID' => $file->fileID,
                'file' => $file->file,
                'status' => $file->status,
                'classRecordID' => $file->classRecordID,
                'updatedAt' => $file->updated_at,
                'professorName' => ($professor ? ($professor->Fname . ' ' . $professor->Lname) : 'N/A'),
                'programTitle' => $file->classrecord->program->programTitle ?? 'N/A',
                'courseTitle' => $file->classrecord->course->courseTitle ?? 'N/A',
            ];
        });

        // $notifications = $user->notifications;
        // $unreadCount = $notifications->whereNull('read_at')->count();

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

        return view('admin.admin-verified-reports', compact('loginID', 'role', 'user', 'userinfo', 'submittedData', 'notifications', 'unreadCount'));
    }


    // public function modifyAndSaveFile($submissionId)
    // {
    //     $submission = SubmittedFile::find($submissionId);
    //     if (!$submission) {
    //         abort(404, 'Submission not found');
    //     }

    //     $oldFilePath = 'public/' . $submission->file;

    //     if (!Storage::exists($oldFilePath)) {
    //         abort(404, 'File not found');
    //     }

    //     // Update the status to 1
    //     $submission->status = 1;
    //     $submission->save();

    //     $pdf = new FpdiFpdi();
    //     $pageCount = $pdf->setSourceFile(Storage::path($oldFilePath));

    //     $loginID = session('loginID');
    //     $user = Login::with('admin')->find($loginID);
    //     $admin = $user ? $user->admin : null;

    //     if ($admin) {
    //         $adminSignature = $admin->signature;
    //     } else {
    //         $adminSignature = 'unknown-signature.png'; // Default or fallback signature
    //     }

    //     // Path to the signature image
    //     $signaturePath = public_path('storage/' . $adminSignature);
    //     $processedSignaturePath = public_path('storage/' . $adminSignature);

    //     // Remove white background using GD
    //     $this->removeWhiteBackgroundGD($signaturePath, $processedSignaturePath);

    //     for ($i = 1; $i <= $pageCount; $i++) {
    //         $templateId = $pdf->importPage($i);
    //         $pdf->AddPage('L');
    //         $pdf->useTemplate($templateId);

    //         // Only add the signature to the last page
    //         if ($i == $pageCount) {
    //             $pageWidth = $pdf->GetPageWidth();
    //             $pageHeight = $pdf->GetPageHeight();
    //             $xPosition = 204; // X position for the signature
    //             $yPosition = $pageHeight - 33; // Y position for the signature

    //             // Add the processed signature image
    //             $pdf->Image($processedSignaturePath, $xPosition + 4, $yPosition, 15, 15, 'PNG'); // Ensure the file format is PNG
    //         }
    //     }

    //     // Save the updated PDF
    //     $outputPath = Storage::path($oldFilePath);
    //     $pdf->Output($outputPath, 'F');

    //     return response()->json(['success' => true, 'message' => 'File updated successfully!']);
    // }

    // public function modifyAndSaveFile($submissionId)
    // {
    //     $submission = SubmittedFile::find($submissionId);
    //     if (!$submission) {
    //         abort(404, 'Submission not found');
    //     }

    //     $oldFilePath = 'grade_files/' . $submission->file;

    //     if (!Storage::exists($oldFilePath)) {
    //         abort(404, 'File not found');
    //     }

    //     // Update the status to 1
    //     $submission->status = 1;
    //     $submission->save();

    //     $pdf = new FpdiFpdi();
    //     $pageCount = $pdf->setSourceFile(Storage::path($oldFilePath));

    //     $loginID = session('loginID');
    //     $user = Login::with('admin')->find($loginID);
    //     $admin = $user ? $user->admin : null;

    //     if ($admin) {
    //         $adminSignature = $admin->signature;
    //     } else {
    //         $adminSignature = 'unknown-signature.png'; // Default or fallback signature
    //     }

    //     // Path to the signature image
    //     $signaturePath = public_path('storage/' . $adminSignature);
    //     $processedSignaturePath = public_path('storage/' . $adminSignature);

    //     // Remove white background using GD
    //     $this->removeWhiteBackgroundGD($signaturePath, $processedSignaturePath);

    //     for ($i = 1; $i <= $pageCount; $i++) {
    //         $templateId = $pdf->importPage($i);
    //         $pdf->AddPage('L');
    //         $pdf->useTemplate($templateId);

    //         // Only add the signature to the last page
    //         if ($i == $pageCount) {
    //             $pageWidth = $pdf->GetPageWidth();
    //             $pageHeight = $pdf->GetPageHeight();
    //             $xPosition = 204; // X position for the signature
    //             $yPosition = $pageHeight - 33; // Y position for the signature

    //             // Add the processed signature image
    //             $pdf->Image($processedSignaturePath, $xPosition + 4, $yPosition, 15, 15, 'PNG'); // Ensure the file format is PNG
    //         }
    //     }

    //     // Save the updated PDF
    //     $outputPath = Storage::path($oldFilePath);
    //     $pdf->Output($outputPath, 'F');

    //     $classRecordID = $submission->classRecordID;
    //     $fileID = $submission->fileID;

    //     // Call the method to send notification
    //     return $this->sendNotificationToFacultyVerifiedFile($classRecordID, $fileID);
    // }

    public function modifyAndSaveFile($submissionId)
    {
        // Find the submission record
        $submission = SubmittedFile::find($submissionId);
        if (!$submission) {
            abort(404, 'Submission not found');
        }

        // Define the file path
        $filePath = 'grade_files/' . $submission->file;
        $fullPath = public_path($filePath);

        // Check if the file exists
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        // Update the status to 1
        $submission->status = 1;
        $submission->save();

        // Initialize FPDI for PDF manipulation
        $pdf = new FpdiFpdi();
        $pageCount = $pdf->setSourceFile($fullPath);

        // Get the current user's login ID and related admin
        $loginID = session('loginID');
        $user = Login::with('admin')->find($loginID);
        $admin = $user ? $user->admin : null;

        // Determine the path to the signature image
        $adminSignature = $admin ? $admin->signature : 'unknown-signature.png';
        $signaturePath = public_path($adminSignature);
        $processedSignaturePath = public_path($adminSignature);

        // Remove white background using GD (assuming this method is defined elsewhere)
        $this->removeWhiteBackgroundGD($signaturePath, $processedSignaturePath);

        // Process each page of the PDF
        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $pdf->AddPage('L');
            $pdf->useTemplate($templateId);

            // Add the signature to the last page only
            if ($i == $pageCount) {
                $pageWidth = $pdf->GetPageWidth();
                $pageHeight = $pdf->GetPageHeight();
                $xPosition = 204; // X position for the signature
                $yPosition = $pageHeight - 33; // Y position for the signature

                // Add the processed signature image
                $pdf->Image($processedSignaturePath, $xPosition + 4, $yPosition, 15, 15, 'PNG'); // Ensure the file format is PNG
            }
        }

        // Save the updated PDF
        $pdf->Output($fullPath, 'F'); // 'F' to save to file

        $classRecordID = $submission->classRecordID;
        $fileID = $submission->fileID;

        // Send notification
        return $this->sendNotificationToFacultyVerifiedFile($classRecordID, $fileID);
    }


    public function sendNotificationToFacultyVerifiedFile($classRecordID, $fileID)
    {
        $adminID = session('loginID');
        $type = 'notif_verified';

        // Ensure classRecordID is present and valid
        if (is_null($classRecordID)) {
            return response()->json(['message' => 'Invalid class record ID.'], 400);
        }

        // Fetch the professor's login ID based on the classRecordID
        $classRecord = ClassRecord::with('login')->find($classRecordID);
        if (!$classRecord || !$classRecord->login) {
            return response()->json(['message' => 'No professor found for the given class record ID.'], 400);
        }

        $professorLoginID = $classRecord->login->loginID;
        $professorLogin = Login::find($professorLoginID);

        // Ensure the professor's login record is found
        if (!$professorLogin) {
            return response()->json(['message' => 'Invalid professor login information.'], 400);
        }

        // Get the admin details for the notification
        $user = Admin::with('login')->where('loginID', $adminID)->first();
        if (!$user || !$user->login) {
            return response()->json(['message' => 'Invalid professor or login information.'], 400);
        }

        // Get the professor's first name and last name
        $adminFname = $user->Fname;
        $adminLname = $user->Lname;

        // Send notification
        $professorLogin->notify(new AdminValidateGradesFile($type, $adminFname, $adminLname, $classRecordID, $fileID));

        // Return success response
        return response()->json(['message' => 'File has been verirfied and notification sent successfully.']);
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


    public function adminOrgChartPage()
    {
        $loginID = session('loginID');
        $role = session('role');
        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;


        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        // $notifications = $user->notifications;
        // $unreadCount = $notifications->whereNull('read_at')->count();

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
        return view('admin.admin-org-chart', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    public function storeEsignature(Request $request)
    {
        // Define validation rules
        $rules = [
            'esignAdmin' => 'required|image|mimes:png|max:2048',
        ];

        // Validate the request data
        $validatedData = $request->validate($rules);

        // Initialize the image path
        $imagePath = null;

        // Check if an image file was uploaded and store it
        // if ($request->hasFile('esignAdmin')) {
        //     $imagePath = $request->file('esignAdmin')->store('esignatures', 'public');
        // }

        if ($request->hasFile('esignAdmin')) {
            $file = $request->file('esignAdmin');

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
        $user = Admin::where('loginID', $loginID)->first();

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

    // public function storeEsignature(Request $request)
    // {
    //     // Define validation rules
    //     $rules = [
    //         'esignAdmin' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    //     ];

    //     // Validate the request data
    //     $validatedData = $request->validate($rules);

    //     // Initialize the image path
    //     $imagePath = null;

    //     if ($request->hasFile('esignAdmin')) {
    //         $file = $request->file('esignAdmin');

    //         // Generate a unique file name
    //         $fileName = $file->getClientOriginalName();

    //         // Define the path where the image will be saved
    //         $directory = 'esignatures';
    //         $fullPath = public_path($directory . '/' . $fileName);
    //         $processedPath = public_path($directory . '/processed_' . $fileName); // Path for the processed image

    //         // Ensure the directory exists
    //         if (!file_exists(public_path($directory))) {
    //             mkdir(public_path($directory), 0755, true);
    //         }

    //         // Save the original image
    //         $file->move(public_path($directory), $fileName);

    //         // Remove the white background
    //         $this->removeWhiteBackgroundGD($fullPath, $processedPath);

    //         // Save the path of the processed image
    //         $imagePath = $directory . '/processed_' . $fileName;
    //     }

    //     // Retrieve the loginID from the session
    //     $loginID = session('loginID');

    //     // Find the user in registration_tbl by loginID
    //     $user = Admin::where('loginID', $loginID)->first();

    //     // If the user is found, update their e-signature path
    //     if ($user) {
    //         $user->update([
    //             'signature' => $imagePath, // Assuming 'eSignature' is the column name for storing the image path
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'E-signature saved successfully!',
    //         ]);
    //     }

    //     // If no user is found, return an error response
    //     return redirect()->back()->with('error', 'User not found!');
    // }

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

    public function downloadFile($id)
    {
        $submittedFile = SubmittedFile::find($id);

        if (!$submittedFile) {
            return redirect()->back()->withErrors(['error' => 'File not found.']);
        }

        $filePath = public_path('storage/grade_files/' . $submittedFile->file);

        if (!file_exists($filePath)) {
            return redirect()->back()->withErrors(['error' => 'File does not exist.']);
        }

        // Flash a success message to the session
        session()->flash('success', 'File downloaded successfully!');

        // Return the file download response
        return response()->download($filePath);
    }

    // public function downloadFile($id)
    // {
    //     $submittedFile = SubmittedFile::find($id);

    //     if (!$submittedFile) {
    //         return redirect()->back()->withErrors(['error' => 'File not found.']);
    //     }

    //     $filePath = storage_path('app/public/grade_files/' . $submittedFile->file);

    //     if (!file_exists($filePath)) {
    //         return redirect()->back()->withErrors(['error' => 'File does not exist.']);
    //     }

    //     session()->flash('success', 'File downloaded successfully!');

    //     return response()->download($filePath);
    // }

    public function sendBatchProfessorCredentials(Request $request)
    {
        $selectedProfIDs = $request->input('selectedProfIDs');

        if (is_null($selectedProfIDs) || !is_array($selectedProfIDs)) {
            return response()->json(['message' => 'Invalid professor IDs.'], 400);
        }

        $professors = Registration::whereIn('loginID', $selectedProfIDs)->with('login')->get();

        foreach ($professors as $professor) {
            $plainPassword = Str::random(8);
            $hashedPassword = Hash::make($plainPassword);

            if ($professor->login) {
                $professor->login->password = $hashedPassword;
                $professor->login->save();

                $professor->isSentCredentials = 1;
                $professor->save();

                $professor->login->notify(new BatchAdminSendFacultyCredentials(
                    $plainPassword,
                    $professor->Fname,
                    $professor->Lname,
                    $professor->Mname,
                    $professor->Sname,
                    $professor->salutation,
                    $professor->login->email
                ));
            }
        }

        return response()->json(['success' => true, 'message' => 'Batch credentials sent successfully.']);
    }

    public function addProgram(Request $request)
    {
        $request->validate([
            'programCode' => 'required|string|max:255',
            'programTitle' => 'required|string|max:255',
        ]);

        try {
            $branch = session('branch');
            $loginID = session('loginID');

            // Create the program
            $program = Programs::create([
                'programCode' => $request->input('programCode'),
                'programTitle' => $request->input('programTitle'),
                'branch' => $branch,
            ]);

            $userAdmin = Login::with('admin')
                ->where('loginID', session('loginID'))
                ->first();
            $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

            // Log the action in the audit trail
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'programs',
                'old_value' => null,
                'new_value' => json_encode([
                    'programCode' => $program->programCode,
                    'programTitle' => $program->programTitle,
                    'branch' => $branch,
                ]),
                'description' => "Created Program:{$program->programTitle}",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Program added successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add program: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function updateProgram(Request $request)
    {
        $request->validate([
            'programID' => 'required|exists:program_tbl,programID',
            'programCode' => 'required|string|max:255',
            'programTitle' => 'required|string|max:255',
        ]);

        try {
            $program = Programs::findOrFail($request->input('programID'));

            // Capture the old values before updating
            $oldValues = [
                'programCode' => $program->programCode,
                'programTitle' => $program->programTitle,
            ];

            // Update the program
            $program->update([
                'programCode' => $request->input('programCode'),
                'programTitle' => $request->input('programTitle'),
            ]);

            // Capture the new values after updating
            $newValues = [
                'programCode' => $program->programCode,
                'programTitle' => $program->programTitle,
            ];

            $userAdmin = Login::with('admin')
                ->where('loginID', session('loginID'))
                ->first();
            $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

            // Log the update action in the audit trail
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Update',
                'table_name' => 'programs',
                'old_value' => json_encode($oldValues),
                'new_value' => json_encode($newValues),
                'description' => "Updated Program: '{$program->programTitle}'.",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Program updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the program.',
            ], 500);
        }
    }


    public function addCourse(Request $request)
    {
        $request->validate([
            'courseCode' => 'required|string|max:255',
            'courseTitle' => 'required|string|max:255',
            'programID' => 'required|exists:program_tbl,programID',
        ]);

        try {
            $courseData = [
                'courseCode' => $request->courseCode,
                'courseTitle' => $request->courseTitle,
                'programID' => $request->programID,
            ];

            // Create the course
            $course = Courses::create($courseData);

            $userAdmin = Login::with('admin')
                ->where('loginID', session('loginID'))
                ->first();
            $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

            // Log the add action in the audit trail
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'course_tbl',
                'old_value' => null,
                'new_value' => json_encode($courseData),
                'description' => "{$userName} Added Course: '{$course->courseTitle}'.",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course added successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add course: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function updateCourse(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'courseID' => 'required|integer|exists:course_tbl,courseID',
            'courseCode' => 'required|string',
            'courseTitle' => 'required|string',
            'programID' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Find the course by ID
        $course = Courses::find($request->courseID);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
            ]);
        }

        // Capture the old data for the audit trail
        $oldData = [
            'courseCode' => $course->courseCode,
            'courseTitle' => $course->courseTitle,
            'programID' => $course->programID,
        ];

        // Update course details
        $course->courseCode = $request->courseCode;
        $course->courseTitle = $request->courseTitle;
        $course->programID = $request->programID;

        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        // Save the updated course
        if ($course->save()) {
            // Capture new data for the audit trail
            $newData = [
                'courseCode' => $course->courseCode,
                'courseTitle' => $course->courseTitle,
                'programID' => $course->programID,
            ];


            // Log the update action in the audit trail
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Update',
                'table_name' => 'courses',
                'old_value' => json_encode($oldData),
                'new_value' => json_encode($newData),
                'description' => "Update Course: '{$course->courseTitle}' updated.",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update course.',
        ]);
    }


    public function displayAccountInfo()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        if ($userinfo && $userinfo->branch) {
            $professors = Registration::whereHas('admin')
                ->select('Fname', 'Lname', 'loginID')
                ->where('branch', $userinfo->branch)
                ->where('adminID', $userinfo->branch)
                ->where('role', 1)
                ->get();
        } else {
            $professors = collect();
        }

        return view('settings-acc-info', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'professors'));
    }

    public function displayUpdatePassword()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        if ($userinfo && $userinfo->branch) {
            $professors = Registration::whereHas('admin')
                ->select('Fname', 'Lname', 'loginID')
                ->where('branch', $userinfo->branch)
                ->where('adminID', $userinfo->branch)
                ->where('role', 1)
                ->get();
        } else {
            $professors = collect();
        }

        return view('settings-pass-info', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount', 'professors'));
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

        $userAdmin = Login::with('admin')
            ->where('loginID', session('loginID'))
            ->first();
        $userName = $userAdmin->admin->Lname . ', ' . $userAdmin->admin->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'registration',
            'description' => "User ".$userName." change password",
            'action_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin password updated successfully!'
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $request->validate([
            'adminID' => 'required|exists:admin_tbl,adminID',
            'loginID' => 'required|exists:login_tbl,loginID',
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Sname' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'salutation' => 'nullable|string|max:255',
        ]);

        // Find the registration records
        $admin = Admin::find($request->adminID);
        $login = Login::find($request->loginID);

        if ($admin && $login) {
            // Update personal information for Admin
            $admin->Fname = $request->Fname;
            $admin->Mname = $request->Mname;
            $admin->Lname = $request->Lname;
            $admin->Sname = $request->Sname;
            $admin->salutation = $request->salutation;
            $admin->save();

            // Update email for Login
            $login->email = $request->email;
            $login->save();

            return response()->json(['success' => true, 'message' => 'Personal information updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Admin or login record not found'], 404);
    }


    public function notifyProfessor(Request $request)
    {
        $professorId = $request->input('professor_id');
        $course = $request->input('course');
        $type = 'notice_faculty';
        $classRecordID = $request->input('classRecordID');


        $professor = Login::find($professorId);

        if ($professor) {
            $professor->notify(new SubmitClassRecordNotice($type, $course, $professor->loginID, $classRecordID));

            return response()->json(['message' => 'Notification sent to professor']);
        }

        return response()->json(['message' => 'Professor not found'], 404);
    }
    public function classRecordYearSem()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('settings-year-semester', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    public function getSchoolYear()
    {
        $loginID = session('loginID');

        $user = Admin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $schoolYear = $user ? $user->schoolYear : null; // Adjust this field based on your database structure
        $semester = $user ? $user->semester : null;
        return response()->json([
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ]);
    }
    public function updateSchoolYearAndSemester(Request $request)
    {
        $validatedData = $request->validate([
            'adminID' => 'required|exists:admin_tbl,adminID',
            'schoolYear' => 'required',
            'semester' => 'required|in:1,2,3',
        ]);

        $admin = Admin::find($validatedData['adminID']);

        if ($admin) {
            $admin->schoolYear = $validatedData['schoolYear'];
            $admin->semester = $validatedData['semester'];
            $admin->save();

            return response()->json(['status' => 'success', 'message' => 'School year and semester updated successfully']);
        }

        // Return an error response if admin is not found
        return response()->json(['status' => 'error', 'message' => 'Admin not found'], 404);
    }

    public function sendNoticeToAdmin(Request $request)
    {
        $admins = Admin::where('branch', 1)->get();

        $type = "notif_faculty_loads";
    
        foreach ($admins as $admin) {
            $login = $admin->login()->first();
    
            if ($login && $login->email) {
                $adminSalutation = $admin->salutation;
                $adminFname = $admin->Fname;          
                $adminLname = $admin->Lname;          
    
                Mail::to($login->email)->send(new SendEmailNotificationFacultyLoads($type, $adminSalutation, $adminLname, $adminFname));
            }
        }
    
        return response()->json(['status' => 'Notifications sent successfully!'], 200);
    }
    
    // public function sendNoticeToAdmin(Request $request)
    // {
    //     // Bus::dispatch(new \App\Jobs\SendAdminNotificationsJob());

    //     $admins = Admin::where('branch', 1)->get();

    //     $data = "Hello";

    //     foreach ($admins as $admin) {
    //         $login = $admin->login()->first();

    //         if ($login && $login->email) {
    //             Notification::route('mail', $login->email)->notify(
    //                 new EmailNotificationAdminIntegration($data)
    //             );
    //         }
    //     }
    // }

    public function sendTestNotification(Request $request)
    {
        try {
            $dataToSend = $request->input('data', 'Hi');  // Ensure the data is correct

            // Send the data to the external API
            $response = Http::post('https://test-ecrs.puptcapstone.com/send-notice', [
                'data' => $dataToSend,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification dispatched successfully!',
                    'response' => $response->json(),
                ]);
            }

            // Handle failure response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send notification.',
                'response' => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            // Catch any errors and return the message
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendAccountEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $email = $request->input('email');
        $message = $request->input('message');

        // Mail::to($email)->send(new FacultyAccountCredentials($message));

        // return redirect()->back()->with('success', 'Email sent successfully.');
    }

    public function displayFacultyLoads()
    {
        $loginID = session('loginID');
        $role = session('role');


        $user = Admin::with(['login'])
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

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('admin.admin-faculty-loads-page', compact('loginID', 'userinfo', 'user', 'role', 'notifications', 'unreadCount'));
    }

    public function getFacultySchedules()
    {
        try {
            // Fetch data from the PUPT API
            $secretKey = env('PUPT_API_SECRET');
            $timestamp = time();
            $nonce = bin2hex(random_bytes(16));
            $method = 'GET';
            $url = 'https://api.pupt-flss.com/api/external/ecrs/v1/pupt-faculty-schedules';
            $body = '';
            $message = $method . '|' . $url . '|' . $body . '|' . $timestamp . '|' . $nonce;
            $signature = hash_hmac('sha256', $message, $secretKey);

            // Make the API request
            $response = Http::withHeaders([
                'X-HMAC-Timestamp' => $timestamp,
                'X-HMAC-Nonce' => $nonce,
                'X-HMAC-Signature' => $signature,
            ])->get($url);

            // Check if the response is successful
            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'academic_year_start' => $data['pupt_faculty_schedules']['academic_year_start'] ?? null,
                    'academic_year_end' => $data['pupt_faculty_schedules']['academic_year_end'] ?? null,
                    'semester' => $data['pupt_faculty_schedules']['semester'] ?? null,
                    'faculties' => $data['pupt_faculty_schedules']['faculties'] ?? [],
                ]);
            }

            // Handle non-200 responses
            return response()->json([
                'error' => true,
                'message' => $response->json('message') ?? 'Failed to fetch faculty schedules',
            ], $response->status());
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
