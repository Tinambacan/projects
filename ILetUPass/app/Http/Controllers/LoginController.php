<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session as FacadesSession;


class LoginController extends Controller
{
    //
    public function login_authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student_num' => [
                'required',
                // 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
            'password' => 'required'
        ], [
            'student_num.required' => 'Student number is required',
            // 'student_num.regex' => 'Student number is invalid. Please provide a valid student number',
            'password.required' => 'Password is required',
        ]);



        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }

        // Attempt to authenticate the user
        $credentials = $request->only('student_num', 'password');
        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Use the relationship to retrieve the registration information
            $userInfo = $user->registration;

            if ($userInfo->role === 1 && $userInfo->isActive === 1) {
                session(['login_ID' => $user->login_ID, 'role' => $userInfo->role]);

                return response()->json(['status' => 'success', 'message' => 'Login successful!', 'redirect_url' => '/ILetYouPass']);
            }
            if ($userInfo->role === 1 && $userInfo->isActive === 2) {
                session(['login_ID' => $user->login_ID, 'role' => $userInfo->role]);
                $userName = $userInfo->first_name;
                return response()->json(['status' => 'success', 'message' => 'Account Disabled!', 'redirect_url' => '/disabled-student-acc']);
            } else {
                // Handle the case where no registration information is found for the user.
                return response()->json(['status' => 'error', 'message' => 'Account not exist']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials']);
        }
    }

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

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Use the relationship to retrieve the registration information
            $userInfo = $user->registration;
            // dd($userInfo->role);
            if ($userInfo->role === 3  && $userInfo->isActive === 1) {
                session(['login_ID' => $user->login_ID, 'role' => $userInfo->role]);

                return response()->json(['status' => 'success', 'message' => 'Login successful!', 'redirect_url' => '/display-game']);
            } else {

                return response()->json(['status' => 'error', 'message' => 'Invalid User']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials']);
        }
    }

    public function prof_authenticate(Request $request)
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

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Use the relationship to retrieve the registration information
            $userInfo = $user->registration;
            // dd($userInfo->role);
            if ($userInfo->role === 2  && $userInfo->isActive === 1) {
                session(['login_ID' => $user->login_ID, 'role' => $userInfo->role]);
                // dd('prof');
                return response()->json(['status' => 'success', 'message' => 'Login successful!', 'redirect_url' => '/display-game']);
            } else {

                return response()->json(['status' => 'error', 'message' => 'Invalid User']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials']);
        }
    }


    public function logout()
    {
        // Auth::logout();
        // session()->flush();

        if (session()->has('login_ID')) {
            $sessionId = session()->pull('login_ID');
            $user = Registration::where('login_ID', '=', $sessionId)->first();
            if ($user->role == 1) {
                return redirect('student-login')->with(['status' => 'success', 'message' => 'Logged Out Successfully!']);
            } elseif ($user->role == 2) {
                return redirect('prof-login')->with(['status' => 'success', 'message' => 'Logged Out Successfully!']);
            } elseif ($user->role == 3) {
                return redirect('admin-login')->with(['status' => 'success', 'message' => 'Logged Out Successfully!']);
            }
        }
    }
}
