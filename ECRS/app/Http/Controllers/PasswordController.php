<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordResetForm;
use App\Models\Admin;
use App\Models\Login;
use App\Models\Registration;
use App\Notifications\SendResetPassLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function sendResetLinkFaculty(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:login_tbl,email',
        ], [
            'email.exists' => 'The email address does not exist in our system.'
        ]);

        $login = DB::table('login_tbl')->where('email', $request->email)->first();

        if (!$login) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $registration = DB::table('registration_tbl')
            ->where('loginID', $login->loginID)
            ->where('role', 1)
            ->select('fname', 'salutation')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $existingToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($existingToken) {
            return response()->json([
                'success' => false,
                'message' => 'Reset password link has been sent. Please check your email.'
            ]);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $resetLink = url('faculty/reset-password/' . $token);

        // Notification::route('mail', $request->email)->notify(new SendResetPassLink($registration->fname, $registration->salutation, $resetLink));

        Mail::to($request->email)->send(new SendPasswordResetForm($registration->fname, $registration->salutation, $resetLink));

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Reset link sent to your email!',
            'redirect' => url('faculty/')
        ]);
    }


    public function sendResetLinkStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:login_tbl,email',
        ], [
            'email.exists' => 'The email address does not exist in our system.'
        ]);

        $login = DB::table('login_tbl')->where('email', $request->email)->first();

        if (!$login) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $registration = DB::table('registration_tbl')
            ->where('loginID', $login->loginID)
            ->where('role', 3)
            ->select('fname', 'salutation')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $existingToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($existingToken) {
            return response()->json([
                'success' => false,
                'message' => 'Reset password link has been sent. Please check your email.'
            ]);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $resetLink = url('student/reset-password/' . $token);

        // Notification::route('mail', $request->email)->notify(new SendResetPassLink($registration->fname, $registration->salutation, $resetLink));

        Mail::to($request->email)->send(new SendPasswordResetForm($registration->fname, $registration->salutation, $resetLink));

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Reset link sent to your email!',
            'redirect' => url('student/')
        ]);
    }

    public function sendResetLinkAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:login_tbl,email',
        ], [
            'email.exists' => 'The email address does not exist in our system.'
        ]);

        $login = DB::table('login_tbl')->where('email', $request->email)->first();

        if (!$login) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $registration = DB::table('admin_tbl')
            ->where('loginID', $login->loginID)
            ->select('fname', 'salutation')
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        $existingToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($existingToken) {
            return response()->json([
                'success' => false,
                'message' => 'Reset password link has been sent. Please check your email.'
            ]);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $resetLink = url('admin/reset-password/' . $token);

        // Notification::route('mail', $request->email)->notify(new SendResetPassLink($registration->fname, $registration->salutation, $resetLink));

        Mail::to($request->email)->send(new SendPasswordResetForm($registration->fname, $registration->salutation, $resetLink));

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Reset link sent to your email!',
            'redirect' => url('admin/')
        ]);
    }


    public function setNewPasswordPage($token)
    {
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$passwordReset) {
            return view('token-expired');
        }

        $email = $passwordReset->email;

        $user = Login::where('email', $email)->first();
        $loginID = $user->loginID;

        $user = Login::where('email', $email)->first();
        $admin = Admin::where('loginID', $loginID)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if ($admin) {
            $role = 2;
        } else {
            $loginID = $user->loginID;
            $registration = DB::table('registration_tbl')->where('loginID', $loginID)->select('role')->first();

            if (!$registration) {
                return view('token-expired');
            }

            $role = $registration->role;
        }

        return view('password-reset', [
            'token' => $token,
            'role' => $role,
        ]);
    }

    public function resetPassword(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed.']);
        }

        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json(['success' => false, 'message' => 'Invalid token.']);
        }

        $email = $passwordReset->email;

        $user = Login::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        DB::table('password_reset_tokens')->where('token', $token)->delete();

        $loginID = $user->loginID;

        $registration = DB::table('registration_tbl')->where('loginID', $loginID)->first();


        if (!$registration) {
            $admin = Admin::where('loginID', $loginID)->first();
            if ($admin) {
                if ($admin->isActive !== 1) {
                    $admin->isActive = 1;
                    $admin->save();
                }
                $role = 2;
            } else {
                return response()->json(['success' => false, 'message' => 'User role not found.']);
            }
        } else {
            $role = $registration->role;
            if ($registration->isActive !== 1) {
                DB::table('registration_tbl')
                    ->where('loginID', $loginID)
                    ->update(['isActive' => 1]);
            }
        }

        switch ($role) {
            case 1:
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/faculty']);
            case 2:
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/admin']);
            case 3:
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/student']);
            default:
                return response()->json(['success' => false, 'message' => 'Unknown role.']);
        }
    }

    public function changeTemporaryPasswordPage(Request $request)
    {
        try {

            $email = decrypt($request->query('email'));
            $role = decrypt($request->query('role'));

            return view('update-temp-pass', compact('email', 'role'));
        } catch (\Exception $e) {

            return redirect()->route('login')->withErrors('Invalid or tampered URL.');
        }
    }

    public function changeTemporaryPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
            'email' => 'required|email',
            'role' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed.']);
        }

        $email = $request->email;

        $user = Login::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        switch ($request->role) {
            case 1: // Faculty
                $registration = Registration::where('loginID', $user->loginID)->first();
                if ($registration) {
                    $registration->isActive = 1;
                    $registration->save();
                }
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/faculty']);

            case 2: // Admin
                $admin = Admin::where('loginID', $user->loginID)->first();
                if ($admin) {
                    $admin->isActive = 1;
                    $admin->save();
                }
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/admin']);

            case 3: // Student
                $registration = Registration::where('loginID', $user->loginID)->first();
                if ($registration) {
                    $registration->isActive = 1;
                    $registration->save();
                }
                return response()->json(['success' => true, 'message' => 'Password updated successfully.', 'redirect' => '/student']);

            default:
                return response()->json(['success' => false, 'message' => 'Unknown role.']);
        }
    }
}
