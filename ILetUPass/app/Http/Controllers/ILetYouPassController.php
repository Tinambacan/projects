<?php

namespace App\Http\Controllers;

use App\Imports\ProfImport;
use App\Imports\StudentImport;
use App\Imports\UsersImport;
use App\Models\Subject;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Score;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

class ILetYouPassController extends Controller
{


    public function ForgotPassStudent()
    {
        return view('student-forgotpass');
    }
    public function ILetYouPass()
    {
        $loginID = session('login_ID');
        $info_students = DB::table('tblregistration')
            ->select(
                'tblregistration.last_name',
                'tblregistration.first_name',
                'tblregistration.middle_name',
                'tblregistration.role',
                'tbllogin.email',
                'tbllogin.profile_photo_path',
                'tbllogin.student_num',
            )
            ->join('tbllogin', 'tblregistration.login_ID', '=', 'tbllogin.login_ID')
            ->where('tblregistration.login_ID', $loginID) // Specify the table name for login_ID
            ->first();
        return view('layout')->with('info_students', $info_students);
    }

    public function LoginStudent()
    {
        return view('login-student');
    }

    public function LoginAdmin()
    {
        return view('login-admin');
    }

    public function LoginProf()
    {
        return view('login-prof');
    }

    public function DisplayStart()
    {
        return view('welcome');
    }

    public function DisplayGame()
    {
        $loginID = session('login_ID');
        $info_students = DB::table('tblregistration')
            ->select(
                'tblregistration.last_name',
                'tblregistration.first_name',
                'tblregistration.middle_name',
                'tblregistration.role',
                'tbllogin.email',
                'tbllogin.profile_photo_path',
                'tbllogin.student_num',
            )
            ->join('tbllogin', 'tblregistration.login_ID', '=', 'tbllogin.login_ID')
            ->where('tblregistration.login_ID', $loginID) // Specify the table name for login_ID
            ->first();
        return view('game')->with('info_students', $info_students);
    }

    public function ScoreRecord()
    {
        $user = auth()->user(); // Assuming you have a login system in place
        $scores = Score::where('login_ID', $user->login_ID)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('score', compact('scores'));
    }


    public function DisplaySubjects(Request $request)
    {

        // $subjects = Subject::whereHas('questions')->get();
        $subjects = Subject::get();
        $subjectName = Subject::pluck('subject_name')->toArray();
        $subjectIDs = $subjects->pluck('subject_ID');

        $highestScores = DB::table('tblscore')
            ->join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->whereIn('tblscore.subject_ID', $subjectIDs)
            ->select('tblscore.subject_ID', DB::raw('MAX(tblscore.score) as max_score'))
            ->groupBy('tblscore.subject_ID')
            ->get();
        $weekStart = Carbon::now()->startOfWeek(); // Start of the current week
        $weekEnd = Carbon::now()->endOfWeek();     // End of the current week
        $firstPlace = DB::table('tblscore')
            ->join('tbllogin', 'tblscore.login_ID', '=', 'tbllogin.login_ID')
            ->join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->leftJoin('tblregistration', 'tbllogin.login_ID', '=', 'tblregistration.login_ID')
            ->select(
                'tblscore.login_ID',
                DB::raw('CONCAT(tblregistration.first_name, " ", tblregistration.last_name) as full_name'),
                DB::raw('SUM(score) as total_score'),
                DB::raw('COUNT(DISTINCT tblscore.subject_ID) as subjects_played'),
                DB::raw('COUNT(tblscore.login_ID) as total_attempts'),
                'tbllogin.profile_photo_path'
            )
            ->whereBetween('tblscore.created_at', [$weekStart, $weekEnd])
            ->groupBy('tblscore.login_ID', 'full_name', 'tbllogin.profile_photo_path')
            ->orderByDesc('total_score')
            ->first();
        $secondPlace = DB::table('tblscore')
            ->join('tbllogin', 'tblscore.login_ID', '=', 'tbllogin.login_ID')
            ->join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->leftJoin('tblregistration', 'tbllogin.login_ID', '=', 'tblregistration.login_ID')

            ->select(
                'tblscore.login_ID',
                DB::raw('CONCAT(tblregistration.first_name, " ", tblregistration.last_name) as full_name'),
                DB::raw('SUM(score) as total_score'),
                DB::raw('COUNT(DISTINCT tblscore.subject_ID) as subjects_played'),
                DB::raw('COUNT(tblscore.login_ID) as total_attempts'),
                'tbllogin.profile_photo_path'
            )
            ->whereBetween('tblscore.created_at', [$weekStart, $weekEnd])
            ->groupBy('tblscore.login_ID', 'full_name', 'tbllogin.profile_photo_path')
            ->orderByDesc('total_score')
            ->skip(1) // Skip the first result (first place)
            ->take(1) // Take the next result (second place)
            ->first();
        $thirdPlace = DB::table('tblscore')
            ->join('tbllogin', 'tblscore.login_ID', '=', 'tbllogin.login_ID')
            ->join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->leftJoin('tblregistration', 'tbllogin.login_ID', '=', 'tblregistration.login_ID')
            ->select(
                'tblscore.login_ID',
                DB::raw('CONCAT(tblregistration.first_name, " ", tblregistration.last_name) as full_name'),
                DB::raw('SUM(score) as total_score'),
                DB::raw('COUNT(DISTINCT tblscore.subject_ID) as subjects_played'),
                DB::raw('COUNT(tblscore.login_ID) as total_attempts'),
                'tbllogin.profile_photo_path'
            )
            ->whereBetween('tblscore.created_at', [$weekStart, $weekEnd])
            ->groupBy('tblscore.login_ID', 'full_name', 'tbllogin.profile_photo_path')
            ->orderByDesc('total_score')
            ->skip(2) // Skip the first result (first place)
            ->take(1) // Take the next result (second place)
            ->first();
        // dd($thirdPlace);
        $lowestScores = DB::table('tblscore')
            ->join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->whereIn('tblscore.subject_ID', $subjectIDs)
            ->select('tblscore.subject_ID', DB::raw('MIN(tblscore.score) as min_score'))
            ->groupBy('tblscore.subject_ID')
            ->get();
        // dd($lowestScores);

        $countSubjectsTaken = Score::join('tblsubject', 'tblscore.subject_ID', '=', 'tblsubject.subject_ID')
            ->join('tbllogin', 'tblscore.login_ID', '=', 'tbllogin.login_ID')
            ->select('tblscore.login_ID', 'tblscore.subject_ID', 'tblsubject.subject_name')
            ->selectRaw('COUNT(tbllogin.login_ID) as countSubjectsTaken')
            ->groupBy('tblscore.login_ID', 'tblscore.subject_ID', 'tblsubject.subject_name')
            ->get();

        return view('subjects', compact('subjects', 'countSubjectsTaken', 'subjectName', 'highestScores', 'lowestScores', 'firstPlace', 'secondPlace', 'thirdPlace'));
    }


    public function DisplayDifficulty($myValue)
    {
        $tbl_subject = Subject::where('subject_ID', $myValue)->get();

        $distinctLevels = Question::where('subject_ID', $myValue)
            ->whereNull('deleted_at')
            ->select('level')
            ->distinct()
            ->pluck('level');

        return view('difficulty', compact('tbl_subject', 'distinctLevels'));
    }

    public function passIndex(Request $request)
    {
        $level = $request->query('difficulty'); // Retrieve as query parameter
        $subjectId = $request->query('subjectId'); // Retrieve as query parameter

        $questions = Question::where('level', $level)
            ->where('subject_ID', $subjectId)
            ->with('answers')
            ->with('subject')
            ->inRandomOrder()
            ->get();


        return view('question', compact('questions'));
    }


    public function SaveScore(Request $request)
    {
        $level = $request->input('difficulty');
        $subjectId = $request->input('subjectId');
        $correctAnswerCount = $request->input('correctAnswerCount');
        $loginID = $request->input('loginID');

        // Create a new Score instance and set its attributes
        $score = new Score();
        $score->score = $correctAnswerCount;
        $score->level = $level;
        $score->login_ID = $loginID;
        $score->subject_ID = $subjectId;

        $score->save();
        return response()->json(['message' => 'Score saved successfully']);
    }

    public function saveSubject(Request $request)
    {
        // Validate the form data
        $request->validate([
            'subject_name' => 'required|string',
            'subject_desc' => 'string',
            'file' => 'image|mimes:jpeg,png,gif',
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file->move('images', $filename);


        $tbl_sub = new Subject();
        $tbl_sub->subject_name = $request->subject_name;
        $tbl_sub->subject_desc = $request->subject_desc;
        $tbl_sub->subject_image = $filename;

        $tbl_sub->save();
        return response()->json(['message' => 'Subject created successfully']);
    }

    public function updateSubject(Request $request)
    {
        // Validate the form data
        $request->validate([
            'subject_name' => 'required|string',
            'subject_desc' => 'string',
            'file' => 'image|mimes:jpeg,png,gif',
        ]);

        // Check if a file was provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->move('images', $filename);
        } else {
            $filename = $request->input('subject_image');
        }

        $subject = Subject::find($request->input('subject_ID'));
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        // Update the subject
        $subject->subject_name = $request->input('subject_name');
        $subject->subject_desc = $request->input('subject_desc');
        $subject->subject_image = $filename;
        $subject->save();

        return response()->json(['message' => 'Subject updated successfully']);
    }

    public function deleteSubject(Request $request)
    {
        // Retrieve the subject by its ID
        $subject = Subject::find($request->input('subject_id'));

        // Check if the subject exists
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        // Soft delete the subject
        $subject->delete();

        // Return a success response
        return response()->json(['message' => 'Subject deleted successfully']);
    }

    public function SignUp()
    {
        return view('signup');
    }

    public function SaveInfoStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fn_nm' => 'required',
            'ls_nm' => 'required',
            'email' => [
                'required',
                'unique:tbllogin',
                // 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
                'regex:/^[A-Za-z0-9._%+-]+@(tup\.edu\.ph|[A-Za-z0-9.-]+\.[Cc][Oo][Mm])$/i',
            ],
        ], [
            'email.required' => 'Email cannot be empty.',
            'email.email' => 'Email is invalid. Please provide a valid email address',
            'email.regex' => 'Email is invalid.',
            'fn_nm.required' => 'First name is required',
            'ls_nm.required' => 'Last name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }


        $tbl_user = new User();
        $password = Str::random(10);
        $tbl_user->password = Hash::make($password);
        $tbl_user->email = $request->email;
        $tbl_user->student_num = $request->stud_num;

        $avatarsDirectory = public_path('avatars');
        $avatarFiles = glob($avatarsDirectory . '/*.png');

        if ($avatarFiles !== false && !empty($avatarFiles)) {
            $randomIndex = array_rand($avatarFiles);
            $selectedAvatarFile = $avatarFiles[$randomIndex];
            $tbl_user->profile_photo_path = basename($selectedAvatarFile);
        } else {
        }
        $res = $tbl_user->save();

        if ($res) {
            $tbl_user_id = $tbl_user->login_ID;

            $tbl_register = new Registration();
            $tbl_register->login_ID = $tbl_user_id;
            $tbl_register->first_name = $request->fn_nm;
            $tbl_register->middle_name = $request->md_nm;
            $tbl_register->last_name = $request->ls_nm;
            $tbl_register->role = 1;
            $tbl_register->isActive = 0;


            $res = $tbl_register->save();

            return response()->json(['status' => 'success', 'message' => 'Student Added Successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error in Adding']);
        }
    }

    // public function captcha(){

    //     return view('testemail');
    // }
    public function studentChangePass(Request $request)
    {
        $pass = $request->input('password');
        $con_pass = $request->input('confirm-password');
        $student = $request->input('student-num');

        if ($pass === $con_pass) {
            $user = User::where('student_num', $student)->first();
            $reg = Registration::where('login_ID', $user->login_ID)->first();
            if ($user) {
                // User with the provided student number exists
                // You should hash the password before storing it in the database
                $user->password = bcrypt($pass);
                $user->save();

                $reg->isActive = 1;
                $reg->save();

                return response()->json(['status' => 'success', 'message' => 'Password updated successfully', 'redirect_url' => '/student-login']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'User not found with the provided student number']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Passwords do not match']);
        }
    }

    public function profChangePass(Request $request)
    {
        $pass = $request->input('password');
        $con_pass = $request->input('confirm-password');
        $prof = $request->input('email');

        if ($pass === $con_pass) {
            $user = User::where('email', $prof)->first();
            $reg = Registration::where('login_ID', $user->login_ID)->first();
                //   dd($prof);
            if ($user) {
                // User with the provided student number exists
                // You should hash the password before storing it in the database
                $user->password = bcrypt($pass);
                // $user->password = $pass;
                $user->save();


                $reg->isActive = 1;
                $reg->save();

                return response()->json(['status' => 'success', 'message' => 'Password updated successfully', 'redirect_url' => '/prof-login']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'User not found with the provided student number']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Passwords do not match']);
        }
    }
    
    public function studentChangeNewPass(Request $request)
    {
        $pass = $request->input('password');
        $con_pass = $request->input('confirm-password');
        $studentEmail = $request->input('email');

        if ($pass === $con_pass) {
            $user = User::where('email', $studentEmail)->first();
            if ($user) {
                // User with the provided student number exists
                // You should hash the password before storing it in the database

                $user->password = bcrypt($pass);
                $user->save();

                return response()->json(['status' => 'success', 'message' => 'Password updated successfully', 'redirect_url' => '/student-login']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'User not found with the provided student number']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Passwords do not match']);
        }
    }
    public function SaveInfoProf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fn_nm' => 'required',
            'ls_nm' => 'required',
            'email' => [
                'required',
                'unique:tbllogin',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
        ], [
            'email.required' => 'Email cannot be empty.',
            'email.email' => 'Email is invalid. Please provide a valid email address',
            'email.regex' => 'Email is invalid.',
            'fn_nm.required' => 'First name is required',
            'ls_nm.required' => 'Last name is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }


        $tbl_user = new User();
        $password = Str::random(10);
        $tbl_user->password = Hash::make($password);
        $tbl_user->email = $request->email;

        $res = $tbl_user->save();

        if ($res) {
            $tbl_user_id = $tbl_user->login_ID;

            $tbl_register = new Registration();
            $tbl_register->login_ID = $tbl_user_id;
            $tbl_register->first_name = $request->fn_nm;
            $tbl_register->middle_name = $request->md_nm;
            $tbl_register->last_name = $request->ls_nm;
            $tbl_register->role = 2;
            $tbl_register->isActive = 0;

            $res = $tbl_register->save();

            return response()->json(['status' => 'success', 'message' => 'Professor Added Successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error in Adding']);
        }
    }


    public function captcha()
    {
        return view('testemail');
    }

    public function DisplayAccSelection()
    {
        return view('acc-selection');
    }

    public function DisplayStudentAcc()
    {
        $tbl_students = DB::table('tblregistration')
            ->select(
                'tblregistration.registration_ID',
                'tblregistration.last_name',
                'tblregistration.first_name',
                'tblregistration.middle_name',
                'tblregistration.login_ID',
                'tblregistration.role',
                'tblregistration.isActive',
                'tbllogin.email',
                'tbllogin.profile_photo_path',
                'tbllogin.student_num',

            )
            ->join('tbllogin', 'tblregistration.login_ID', '=', 'tbllogin.login_ID')
            ->where('tblregistration.role', 1)
            ->whereNull('tbllogin.deleted_at')
            ->get();
        $tbl_user = User::select('login_ID', 'email')->get();


        return view('student-accounts', compact('tbl_students'));
    }

    public function DisplayProfAcc()
    {
        $tbl_prof = DB::table('tblregistration')
            ->select(
                'tblregistration.registration_ID',
                'tblregistration.last_name',
                'tblregistration.first_name',
                'tblregistration.middle_name',
                'tblregistration.login_ID',
                'tblregistration.role',
                'tblregistration.isActive',
                'tbllogin.email',
            )
            ->join('tbllogin', 'tblregistration.login_ID', '=', 'tbllogin.login_ID')
            ->where('tblregistration.role', 2)
            ->whereNull('tbllogin.deleted_at')
            ->get();
        $tbl_user = User::select('login_ID', 'email')->get();


        return view('prof-accounts', compact('tbl_prof'));
    }

    public function studentImport(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,excel|max:10240',
        ], [
            'file.mimes' => 'CSV, Excel or XLSX only',
            'file.required' => 'File is required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        try {
            Excel::import(new StudentImport($request->id), $request->file('file'));

            return response()->json(['status' => 'success', 'message' => 'File imported successfully']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return response()->json(['status' => 'error', 'failures' => $errorMessages]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deactivateStudent(Request $request)
    {

        $selectedIds = $request->input('student_ids');

        Registration::whereIn('login_ID', $selectedIds)
            ->update(['isActive' => 2]);

        return response()->json(['status' => 'success', 'message' => 'User Deactivated Successfully']);
    }

    public function reactivateStudent(Request $request)
    {
        $selectedIds2 = $request->input('student_ids2');

        Registration::whereIn('login_ID', $selectedIds2)
            ->update(['isActive' => 1]);

        return response()->json(['status' => 'success', 'message' => 'User Reactivated Successfully']);
    }

    public function deleteBatchStudent(Request $request)
    {
        $selectedIds3 = $request->input('student_ids3');

        User::whereIn('login_ID', $selectedIds3)->delete();

        return response()->json(['status' => 'success', 'message' => 'User Deleted Successfully']);
    }

    public function deactivateProf(Request $request)
    {

        $selectedIds = $request->input('prof_ids');

        Registration::whereIn('login_ID', $selectedIds)
            ->update(['isActive' => 0]);

        return response()->json(['status' => 'success', 'message' => 'User Deactivated Successfully']);
    }



    public function reactivateProf(Request $request)
    {
        $selectedIds2 = $request->input('prof_ids2');

        Registration::whereIn('login_ID', $selectedIds2)
            ->update(['isActive' => 1]);

        return response()->json(['status' => 'success', 'message' => 'User Reactivated Successfully']);
    }

    public function deleteBatchProf(Request $request)
    {
        $selectedIds3 = $request->input('prof_ids3');

        User::whereIn('login_ID', $selectedIds3)->delete();

        return response()->json(['status' => 'success', 'message' => 'User Deleted Successfully']);
    }


    public function updateStudentInfo(Request $request)
    {
        // Validate the form data
        // $request->validate([
        //     'subject_name' => 'required|string',
        //     'subject_desc' => 'string',
        //     'file' => 'image|mimes:jpeg,png,gif',
        // ]);

        $tbl_user = User::find($request->input('stud_ID'));
        $tbl_user->email = $request->input('email');
        $tbl_user->student_num = $request->input('stud_num');

        $res = $tbl_user->save();

        if ($res) {
            $tbl_register = Registration::find($request->input('stud_ID'));
            $tbl_register->first_name =  $request->input('first_name');
            $tbl_register->middle_name = $request->input('middle_name');
            $tbl_register->last_name =  $request->input('last_name');

            $tbl_register->save();

            return response()->json(['status' => 'success', 'message' => 'Student Information Updated Successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error in Updating']);
        }
    }


    public function updateProfInfo(Request $request)
    {
        // Validate the form data
        // $request->validate([
        //     'subject_name' => 'required|string',
        //     'subject_desc' => 'string',
        //     'file' => 'image|mimes:jpeg,png,gif',
        // ]);

        $tbl_user = User::find($request->input('prof_ID'));
        $tbl_user->email = $request->input('email');

        $res = $tbl_user->save();

        if ($res) {
            $tbl_register = Registration::find($request->input('prof_ID'));
            $tbl_register->first_name =  $request->input('first_name');
            $tbl_register->middle_name = $request->input('middle_name');
            $tbl_register->last_name =  $request->input('last_name');

            $tbl_register->save();

            return response()->json(['status' => 'success', 'message' => 'Professor Information Updated Successfully!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Error in Updating']);
        }
    }

    public function deleteStudent($stud_id)
    {

        $tbl_user = User::find($stud_id);


        if ($tbl_user) {
            $tbl_user->delete();
            $tbl_user = User::withTrashed()->get();

            return response()->json(['status' => 'success', 'message' => 'Student deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Student not found.']);
        }
    }

    public function deleteProf($prof_id)
    {

        $tbl_user = User::find($prof_id);


        if ($tbl_user) {
            $tbl_user->delete();
            $tbl_user = User::withTrashed()->get();

            return response()->json(['status' => 'success', 'message' => 'Professor deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Professor not found.']);
        }
    }

    public function profImport(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,excel|max:10240',
        ], [
            'file.mimes' => 'CSV, Excel or XLSX only',
            'file.required' => 'File is required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        try {
            Excel::import(new ProfImport($request->id), $request->file('file'));

            return response()->json(['status' => 'success', 'message' => 'File imported successfully']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return response()->json(['status' => 'error', 'failures' => $errorMessages]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function DisplayManageQuesAns(Request $request, $myValue)
    {

        $mySubject = session('subject_ID'); // Retrieve as query parameter
        $tbl_subject = Subject::where('subject_ID', $myValue)->get();
        $tbl_question_ans = Question::where('subject_ID', $myValue)
            ->with('subject')
            ->get();


        $incorrectAnswers = [];
        foreach ($tbl_question_ans as $question) {
            $incorrectAnswers[$question->question_ID] = $question->answers
                ->where('answer', 0)
                ->pluck('choices_desc', 'answer_ID')
                ->toArray();
        }

        $correctAnswers = [];
        foreach ($tbl_question_ans as $question) {
            $correctAnswers[$question->question_ID] = $question->answers
                ->where('answer', 1)
                ->first();
        }


        return view('manage-ques-ans', compact('tbl_subject', 'tbl_question_ans', 'incorrectAnswers', 'correctAnswers'));
    }

    // public function passIndexSubject($myValue)
    // {

    //     $tbl_question_ans = Question::where('subject_ID', $myValue)
    //         ->with('answers')
    //         ->with('subject')
    //         ->get();

    //     return view('manage-ques-ans', compact('tbl_question_ans'));
    // }


    public function filterSubject(Request $request)
    {
        $mySubject = $request->input('myKey');
        session(['subject_ID' => $mySubject]);
    }
    public function filterLevels(Request $request)
    {
        $myLevel = $request->input('levels');
        session(['levels' => $myLevel]);
    }

    public function addQuestion(Request $request, $myValue)
    {
        // $validatedData = $request->validate([]);


        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'levels' => 'required',
            'choice' => 'required',
        ], [
            'question.required' => 'Question is required',
            'levels.required' => 'Level is required',
            'choice.required' => 'Choose for the correct answer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        // Create the question
        $question = new Question;
        $question->question_desc = $request->input('question');
        $question->question_exp = $request->input('explanation');
        $question->level = $request->input('levels');
        $question->subject_ID = $myValue;
        $question->save();

        $selectedChoice = $request->input('choice');

        for ($i = 1; $i <= 4; $i++) {
            $choice = new Answer;
            $choice->choices_desc = $request->input("answer$i");
            $choice->question_ID = $question->question_ID;

            // Check if this choice is the correct answer
            if ($selectedChoice == $i) {
                $choice->answer = 1;
            } else {
                $choice->answer = 0;
            }

            $choice->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Question and answers added successfully']);
    }

    public function updateQuestion(Request $request)
    {
        $validatedData = $request->validate([
            'question_desc' => 'required',
            'level' => 'required',
            'edit-answer1' => 'required',
            'edit-answer2' => 'required',
            'edit-answer3' => 'required',
            'edit-answer4' => 'required',
            'choice' => 'required', // This is the selected choice
        ]);

        $questionID = $request->input('question_ID');
        // Update the question
        $question = Question::find($questionID);

        $question->question_desc = $request->input('question_desc');
        $question->question_exp = $request->input('question_exp');
        $question->level = $request->input('level');

        $question->save();

        // Get the selected choice from the form
        $selectedChoice = $request->input('choice');

        // Update the answer choices
        for ($i = 1; $i <= 4; $i++) {
            $answerID = $request->input("edit-answer{$i}-ID"); // Assuming you have answer IDs for each choice
            $choice = Answer::find($answerID);


            $choice->choices_desc = $request->input("edit-answer{$i}");

            $choice->answer = ($selectedChoice == $i) ? 1 : 0;
            $choice->save(); // Update the choice in the database
        }

        return response()->json(['status' => 'success', 'message' => 'Question and answers updated successfully']);
    }

    public function deleteQuestion(Request $request)
    {
        $questionID = $request->input('question_ID');
        $question = Question::withTrashed()->find($questionID);

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $question->delete();
        $question->answers()->delete();

        return response()->json(['status' => 'success', 'message' => 'Question deleted successfully']);
    }

    public function questionImport(Request $request, $myValue)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,xlsx,excel|max:10240',
            ], [
                'file.mimes' => 'CSV, Excel or XLSX only',
                'file.required' => 'File is required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
            }
    
            $import = new QuestionImport($myValue);
                Excel::import($import, $request->file('file'));

                $invalidRows = $import->getInvalidRows();

                if (!empty($invalidRows)) {
                    // Throw a ValidationException with all invalid rows
                    throw ValidationException::withMessages(['rows' => "Validation failed for rows: " . implode(', ', $invalidRows)]);
                }
            
            return response()->json(['status' => 'success', 'message' => 'File imported successfully']);
        } catch (ValidationException $e) {
            $validationErrors = $e->errors();
            $flattenedErrors = is_array($validationErrors) ? $validationErrors : [$validationErrors];
    
            $errorMessage = 'Validation failed. ' . implode(' ', Arr::flatten($flattenedErrors));
    
            return response()->json([
                'status' => 'error',
                'message' => rtrim($errorMessage),
            ]);
        } catch (\Exception $e) {

            $errorMessage = 'An error occurred. ' . $e->getMessage(); 
        
            return response()->json([
                'status' => 'error',
                'message' => rtrim($errorMessage),
            ]); 
        }
    }
    public function DisplayStudentInfo()
    {

        return view('student-info');
    }
    public function search(Request $request)
    {
        // Retrieve the student number from the request
        $studentNumber = $request->input('stud_num');

        $users = DB::table('tbllogin')
            ->join('tblscore', 'tblscore.login_ID', '=', 'tbllogin.login_ID')
            ->join('tblregistration', 'tblregistration.login_ID', '=', 'tbllogin.login_ID')
            ->leftjoin('tblsubject', 'tblsubject.subject_ID', '=', 'tblscore.subject_ID')
            ->select('tblregistration.first_name', 'tblregistration.last_name', 'tbllogin.student_num', 'tbllogin.email', 'tblsubject.subject_name', 'tbllogin.profile_photo_path', 'tblscore.score', 'tblscore.created_at', 'tblscore.level')
            ->where('tbllogin.student_num', $studentNumber)
            ->get(); // Retrieve all matching rows
        if ($users->isNotEmpty()) {
            // Users exist, return the data as an array
            $userArray = [];
            foreach ($users as $user) {
                $userArray[] = [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'student_num' => $user->student_num,
                    'subject_name' => $user->subject_name,
                    'level' => $user->level,
                    'score' => $user->score,
                    'created_at' => $user->created_at,
                    'email' => $user->email,
                    'profile_photo_path' => asset('avatars/' . $user->profile_photo_path)
                ];
            }

            return response()->json(['exists' => true, 'users' => $userArray]);
        } else {
            // User doesn't exist, return a response indicating that
            return response()->json(['exists' => false, 'message' => 'User not found']);
        }
    }
}
