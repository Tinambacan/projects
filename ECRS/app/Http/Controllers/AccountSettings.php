<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountSettings extends Controller
{
    public function forgotPasswordPageStudent()
    {
        return view('student.send-email-pass');
    }

    public function forgotPasswordPageFaculty()
    {
        return view('faculty.send-email-pass');
    }

    public function forgotPasswordPageAdmin()
    {
        return view('admin.send-email-pass');
    }

    public function clearSession(Request $request)
    {
        session()->flush();
        return response()->json(['status' => 'Session cleared']);
    }
}
