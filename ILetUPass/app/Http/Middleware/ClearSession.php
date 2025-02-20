<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
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
