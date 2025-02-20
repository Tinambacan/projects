<?php

namespace App\Http\Controllers;

use App\Mail\AccountActivation;
use App\Mail\ProfActivation;
use App\Mail\EmailSubscriber;
use App\Mail\StudentForgotPass;
use App\Mail\StudentForgotPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class EmailController extends Controller
{
    //
    // public function test(){
        
    //     $imgPath = public_path('images/LogoPNG.png');
    //     $imgUrl = asset('images/LogoPNG.png'); // Assuming the image is in the public folder.
    
    //     return "<html><body><img src='$imgUrl' alt='Logo'></body></html>";;
    // }
    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
            'captcha' => 'required|captcha',
        ], [
            'email.required' => 'Email is required',
            'email.regex' => 'Email is invalid. Please provide a valid email address',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }
        $email = $request->all()['email'];
  
        $subscriber = User::where('email', $email)->first();
        // $imgUrl = asset('images/LogoPNG.png');
        if ($subscriber) {
            $studentNum = $subscriber->student_num;
            Mail::to($email)->send(new AccountActivation ($email,$studentNum));
            return response()->json(['status' => 'success', 'message' => 'Account Activation has been sent!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User does not Exist']);
        }
    }

    public function profSubscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
            'captcha' => 'required|captcha',
        ], [
            'email.required' => 'Email is required',
            'email.regex' => 'Email is invalid. Please provide a valid email address',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }
        $email = $request->all()['email'];
  
        $subscriber = User::where('email', $email)->first();
        // $imgUrl = asset('images/LogoPNG.png');
        if ($subscriber) {
            // dd($email);
            Mail::to($email)->send(new ProfActivation ($email));
            return response()->json(['status' => 'success', 'message' => 'Account Activation has been sent!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User does not Exist']);
        }
    }
    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }

    public function studentForgotPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[Cc][Oo][Mm]$/',
            ],
        ], [
            'email.required' => 'Email is required',
            'email.regex' => 'Email is invalid. Please provide a valid email address',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()]);
        }
        $email = $request->all()['email'];

        $subscriber = User::where('email', $email)->first();

        if ($subscriber) {
            $studentNum = $subscriber->student_num;
            // dd($studentNum);
            Mail::to($email)->send(new StudentForgotPass($email, $studentNum));
            return response()->json(['status' => 'success', 'message' => 'Forgot password email has been sent!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'User not Exist']);
        }
    }
}
