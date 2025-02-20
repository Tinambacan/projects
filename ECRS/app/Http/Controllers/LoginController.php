<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ClassRecord;
use App\Models\Registration;
use App\Models\Login;
use App\Models\Student;
use App\Models\SuperAdmin;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{

    public function faculty_authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_schoolIDNo' => [
                'required',
                'regex:/^(?:[A-Za-z]{2}\d{4}[A-Za-z]{2}\d{4}|[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[a-zA-Z]{2,})$/',
            ],
            'password' => 'required',
        ], [
            'email_or_schoolIDNo.required' => 'Email or School ID is required',
            'email_or_schoolIDNo.regex' => 'Invalid Email or School ID format',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        $credentials = $request->only('email_or_schoolIDNo', 'password');

        if (filter_var($credentials['email_or_schoolIDNo'], FILTER_VALIDATE_EMAIL)) {
            $user = Login::where('email', $credentials['email_or_schoolIDNo'])->first();
        } else {
            $user = Login::whereHas('registration', function ($query) use ($credentials) {
                $query->where('schoolIDNo', $credentials['email_or_schoolIDNo']);
            })->first();
        }

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found']);
        }

        $classRecords = ClassRecord::with(['program', 'course'])
            ->where('loginID', $user->loginID)
            ->where('isArchived', 0)
            ->latest('created_at')
            ->get();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $registration = $user->registration;

            if ($registration && $registration->role === 1) {
                $userFaculty = Registration::where('loginID', $user->loginID)->first();
                if ($userFaculty) {
                    if ($userFaculty->isActive === 0) {
                        $encryptedEmail = encrypt($user->email);
                        $encryptedRole = encrypt(1);

                        $redirectUrl = route('faculty.change-temp-pass', [
                            'email' => $encryptedEmail,
                            'role' => $encryptedRole,
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => $redirectUrl,
                        ]);
                    }

                    session([
                        'loginID' => $user->loginID,
                        'role' => $registration->role,
                        'branch' => $registration->branch,
                        'userinfo' => $user->registration,
                        'user' => $user,
                        'classRecords' => $classRecords,
                        'api_key' => env('API_KEY')
                    ]);

                    AuditTrail::create([
                        'record_id' => $user->loginID,
                        'user' => $userFaculty->Lname . ',' . $userFaculty->Fname,
                        'action' => 'Login',
                        'table_name' => 'login',
                        'description' => "Faculty: {$registration->Lname},{$registration->Fname} Successfully Login",
                        'action_time' => Carbon::now(),
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Faculty login successful!',
                        'redirect_url' => route('faculty.class-record'),
                        'loginID' => $user->loginID,
                    ]);
                }
            } else {
                Log::warning('Unauthorized role attempted login', [
                    'email' => $credentials['email_or_schoolIDNo'],
                    'role' => $registration->role,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized role'
                ]);
            }
        } else {
            Log::warning('Invalid login attempt', [
                'email' => $credentials['email_or_schoolIDNo'],
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials'
            ]);
        }
    }

    public function student_authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'studentNo' => [
                'required',
                'string',
                'regex:/^\d{4}-\d{5}-[A-Z]{2}-\d{1}$/', // Allow only alphanumeric characters
            ],
            'password' => 'required'
        ], [
            'studentNo.required' => 'Student Number is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        // $credentials = $request->only('studentNo', 'password');

        // $student = Student::where('studentNo', $credentials['studentNo'])->first();

        // if (!$student) {
        //     return response()->json(['status' => 'error', 'message' => 'Student not found']);
        // }

        // $login = Login::where('email', $student->email)->first();

        // if (!$login) {
        //     return response()->json(['status' => 'error', 'message' => 'User not found']);
        // }

        $credentials = $request->only('studentNo', 'password');

        $student = Student::where('studentNo', $credentials['studentNo'])->first();

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Student not found']);
        }

        $registration = Registration::where('schoolIDNo', $student->studentNo)->first();

        if (!$registration) {
            return response()->json(['status' => 'error', 'message' => 'Registration record not found']);
        }

        $login = Login::where('loginID', $registration->loginID)->first();

        if (!$login) {
            return response()->json(['status' => 'error', 'message' => 'Login record not found']);
        }

        if (Hash::check($credentials['password'], $login->password)) {
            $registration = $login->registration;

            if ($registration && $registration->role == 3) {
                // if ($registration->isActive == 0) {
                //     $registration->isActive = 1;
                //     $registration->save();
                // }

                // session([
                //     'loginID' => $login->loginID,
                //     'role' => $registration->role,
                //     'studentNo' => $student->studentNo,
                // ]);

                // AuditTrail::create([
                //     'record_id' => $login->loginID,
                //     'user' => $registration->schoolIDNo,
                //     'action' => 'Login',
                //     'table_name' => 'login',
                //     'description' => "Student: {$registration->schoolIDNo} Successfully Login",
                //     'action_time' => Carbon::now(),
                // ]);


                // return response()->json([
                //     'status' => 'success',
                //     'message' => 'Login successful!',
                //     'redirect_url' => '/student/dashboard',
                //     'loginID' => $student->loginID,
                // ]);

                $userStudent = Registration::where('loginID', $login->loginID)->first();
                if ($userStudent) {
                    if ($userStudent->isActive === 0) {
                        $encryptedEmail = encrypt($login->email);
                        $encryptedRole = encrypt(3);

                        $redirectUrl = route('student.change-temp-pass', [
                            'email' => $encryptedEmail,
                            'role' => $encryptedRole,
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => $redirectUrl,
                        ]);
                    }

                    session([
                        'loginID' => $login->loginID,
                        'role' => $registration->role,
                        'studentNo' => $student->studentNo,
                        'api_key' => env('API_KEY')
                    ]);


                    AuditTrail::create([
                        'record_id' => $login->loginID,
                        'user' => $registration->schoolIDNo,
                        'action' => 'Login',
                        'table_name' => 'login',
                        'description' => "Student: {$registration->schoolIDNo} Successfully Login",
                        'action_time' => Carbon::now(),
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Student login successful!',
                        'redirect_url' => route('student.dashboard'),
                        'loginID' => $student->loginID,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized role',
                ]);
            }
        } else {
            Log::warning('Invalid login attempt', [
                'studentNo' => $credentials['studentNo'],
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials',
            ]);
        }
    }


    // public function admin_authenticate(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => [
    //             'required',
    //             'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
    //         ],
    //         'password' => 'required'
    //     ], [
    //         'email.required' => 'Email is required',
    //         'email.regex' => 'Email is invalid. Please provide a valid email address',
    //         'password.required' => 'Password is required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     $user = Login::where('email', $credentials['email'])->first();

    //     if (!$user) {
    //         return response()->json(['status' => 'error', 'message' => 'User not found']);
    //     }

    //     if ($user && Hash::check($credentials['password'], $user->password)) {

    //         $userSuperAdmin = SuperAdmin::where('loginID', $user->loginID)->first();
    //         if ($userSuperAdmin) {
    //             $userSuperAdmin->update(['isActive' => 1]);

    //             session([
    //                 'loginID' => $userSuperAdmin->loginID,
    //                 'role' => 4,
    //                 'user' => $user,
    //             ]);

    //             AuditTrail::create([
    //                 'record_id' => $user->loginID,
    //                 'user' => $userSuperAdmin->Lname . ',' . $userSuperAdmin->Fname,
    //                 'action' => 'Login',
    //                 'table_name' => 'login',
    //                 'description' => "Super Admin: {$userSuperAdmin->Lname}, {$userSuperAdmin->Fname} Successfully Login",
    //                 'action_time' => Carbon::now(),
    //             ]);

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Super admin login successful!',
    //                 'redirect_url' => route('super.accounts'),
    //                 'loginID' => $user->loginID,
    //             ]);
    //         }

    //         $userAdmin = Admin::where('loginID', $user->loginID)->first();
    //         if ($userAdmin) {
    //             $userAdmin->update(['isActive' => 1]);

    //             session([
    //                 'loginID' => $userAdmin->loginID,
    //                 'role' => 2,
    //                 'branch' => $userAdmin->branch,
    //                 'user' => $user,
    //             ]);

    //             AuditTrail::create([
    //                 'record_id' => $user->loginID,
    //                 'user' => $userAdmin->Lname . ',' . $userAdmin->Fname,
    //                 'action' => 'Login',
    //                 'table_name' => 'login',
    //                 'description' => "HAP: {$userAdmin->Lname}, {$userAdmin->Fname} Login Successfully",
    //                 'action_time' => Carbon::now(),
    //             ]);

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Admin login successful!',
    //                 'redirect_url' => route('admin.accounts'),
    //                 'loginID' => $user->loginID,
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Admin details not found.'
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Invalid Credentials'
    //     ]);
    // }

    public function admin_authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
            'password' => 'required'
        ], [
            'email.required' => 'Email is required',
            'email.regex' => 'Email is invalid. Please provide a valid email address',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        $credentials = $request->only('email', 'password');

        $user = Login::where('email', $credentials['email'])->first();


        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found']);
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {

            $userSuperAdmin = SuperAdmin::where('loginID', $user->loginID)->first();
            if ($userSuperAdmin) {

                session([
                    'loginID' => $userSuperAdmin->loginID,
                    'role' => 4,
                    'user' => $user,
                    'api_key' => env('API_KEY'),
                    'last_activity' => Carbon::now(),
                ]);

                AuditTrail::create([
                    'record_id' => $user->loginID,
                    'user' => $userSuperAdmin->Lname . ',' . $userSuperAdmin->Fname,
                    'action' => 'Login',
                    'table_name' => 'login',
                    'description' => "Super Admin: {$userSuperAdmin->Lname}, {$userSuperAdmin->Fname} Successfully Login",
                    'action_time' => Carbon::now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Super admin login successful!',
                    'redirect_url' => route('super.accounts'),
                    'loginID' => $user->loginID,
                ]);
            }

            $userAdmin = Admin::where('loginID', $user->loginID)->first();
            if ($userAdmin) {
                if ($userAdmin->isActive === 0) {
                    $encryptedEmail = encrypt($user->email);
                    $encryptedRole = encrypt(2);

                    $redirectUrl = route('admin.change-temp-pass', [
                        'email' => $encryptedEmail,
                        'role' => $encryptedRole,
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'redirect_url' => $redirectUrl,
                    ]);
                }

                session([
                    'loginID' => $userAdmin->loginID,
                    'role' => 2,
                    'branch' => $userAdmin->branch,
                    'user' => $user,
                    'api_key' => env('API_KEY')
                ]);

                AuditTrail::create([
                    'record_id' => $user->loginID,
                    'user' => $userAdmin->Lname . ',' . $userAdmin->Fname,
                    'action' => 'Login',
                    'table_name' => 'login',
                    'description' => "HAP: {$userAdmin->Lname}, {$userAdmin->Fname} Login Successfully",
                    'action_time' => Carbon::now(),
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Admin login successful!',
                    'redirect_url' => route('admin.accounts'),
                    'loginID' => $user->loginID,
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Admin details not found.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid Credentials',
        ]);
    }

    // public function admin_authenticate(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => [
    //             'required',
    //             'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
    //         ],
    //         'password' => 'required'
    //     ], [
    //         'email.required' => 'Email is required',
    //         'email.regex' => 'Email is invalid. Please provide a valid email address',
    //         'password.required' => 'Password is required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     if (!Auth::attempt($credentials)) {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid credentials']);
    //     }

    //     $user = Auth::user();
    //     $userSuperAdmin = SuperAdmin::where('loginID', $user->loginID)->first();

    //     if ($userSuperAdmin) {
    //         session([
    //             'loginID' => $userSuperAdmin->loginID,
    //             'role' => 4,
    //             'user' => $user,
    //             'api_key' => env('API_KEY'),
    //             'last_activity' => now(),
    //         ]);

    //         AuditTrail::create([
    //             'record_id' => $user->loginID,
    //             'user' => $userSuperAdmin->Lname . ',' . $userSuperAdmin->Fname,
    //             'action' => 'Login',
    //             'table_name' => 'login',
    //             'description' => "Super Admin: {$userSuperAdmin->Lname}, {$userSuperAdmin->Fname} Successfully Login",
    //             'action_time' => now(),
    //         ]);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Super admin login successful!',
    //             'redirect_url' => route('super.accounts'),
    //             'loginID' => $user->loginID,
    //         ]);
    //     }

    //     return response()->json(['status' => 'error', 'message' => 'Unauthorized']);
    // }





    public function logout()
    {
        if (session()->has('loginID')) {
            $loginID = session()->get('loginID');
            $role = session()->get('role');

            // Determine user name based on role
            $userName = 'Unknown User';
            if (in_array($role, [1, 3])) {
                $user = Login::with('registration')->where('loginID', $loginID)->first();
                $userName = $user && $user->registration ? $user->registration->Lname . ', ' . $user->registration->Fname : $userName;
            } elseif ($role == 2) {
                $user = Login::with('admin')->where('loginID', $loginID)->first();
                $userName = $user && $user->admin ? $user->admin->Lname . ', ' . $user->admin->Fname : $userName;
            } elseif ($role == 4) {
                $user = Login::with('superadmin')->where('loginID', $loginID)->first();
                $userName = $user && $user->superadmin ? $user->superadmin->Lname . ', ' . $user->superadmin->Fname : $userName;
            }

            // Log the logout action in the audit trail
            AuditTrail::create([
                'record_id' => $loginID,
                'user' => $userName,
                'action' => 'Logout',
                'table_name' => 'sessions', // Using 'sessions' table to represent logout
                'old_value' => null,
                'new_value' => null,
                'description' => "{$userName} logged out.",
                'action_time' => Carbon::now(),
            ]);

            session()->flush();

            // Determine redirect URL based on role
            $redirectUrl = match ($role) {
                1 => url('faculty'),
                2, 4 => url('admin'),
                3 => url('student'),
                default => url('/')
            };

            return redirect($redirectUrl)->with(['status' => 'success', 'message' => 'Logged Out Successfully!']);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No session found!',
                'redirect_url' => url('/')
            ]);
        }
    }
}
