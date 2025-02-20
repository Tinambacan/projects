<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class IdleTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $idleTimeout = 1; 

    public function handle(Request $request, Closure $next): Response
    {
        $lastActivity = Session::get('last_activity');


        $inactiveTime = Carbon::parse($lastActivity)->diffInMinutes(now());

        if ($inactiveTime > $this->idleTimeout) {
            return $this->logoutUser($request, 'You have been logged out due to inactivity.');
        }

        Session::put('last_activity', now());

        return $next($request);
    }

    private function logoutUser(Request $request, $message)
    {
        $role = session('role');
    
        Session::flush();
    
        $redirectUrl = match ($role) {
            1 => url('faculty'),
            2, 4 => url('admin'),
            3 => url('student'),
            default => route('login')
        };
    
        return redirect($redirectUrl)->with(['status' => 'error', 'message' => $message]);

    }
    
}
