<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!session()->has('login_ID')  && (url('display-game') == $request->url() && url('ILetYouPass') == $request->url())) {
            return redirect('/student-login')
                ->with(['status' => 'warning', 'message' => 'You have to log in first!']);
        }
        
        if (!session()->has('login_ID')  && (url('display-game') == $request->url())) {
            return redirect('/admin-login')
                ->with(['status' => 'warning', 'message' => 'You have to log in first!']);
        }

        if (!session()->has('login_ID')  && (url('display-game') == $request->url())) {
            return redirect('/prof-login')
                ->with(['status' => 'warning', 'message' => 'You have to log in first!']);
        }


        return $next($request);
    }
}
