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

        if (!session()->has('loginID')) {
            if ($request->is('faculty/*')) {
                return redirect('faculty')
                    ->with('status', 'warning')->with('warning', 'You have to log in first!');
            }

            if ($request->is('student/*')) {
                return redirect('student')
                    ->with('status', 'warning')->with('warning', 'You have to log in first!');
            }

            if ($request->is('admin/*')) {
                return redirect('admin')
                    ->with('status', 'warning')->with('warning', 'You have to log in first!');
            }

            if ($request->is('superadmin/*')) {
                return redirect('admin')
                    ->with('status', 'warning')->with('warning', 'You have to log in first!');
            }
        } else {
            $userRole = session('role');

            if ($userRole == 1 && $request->is('admin/*')) {
                return redirect('/faculty/class-record')->with('status', 'warning')->with('warning', 'Access denied to admin panel.');
            }

            if ($userRole == 1 && $request->is('student/*')) {
                return redirect('/faculty/class-record')->with('status', 'warning')->with('warning', 'Access denied to student panel.');
            }

            if ($userRole == 2 && $request->is('faculty/*')) {
                return redirect('/admin/accounts')->with('status', 'warning')->with('warning', 'Access denied to faculty panel.');
            }

            if ($userRole == 2 && $request->is('student/*')) {
                return redirect('/admin/accounts')->with('status', 'warning')->with('warning', 'Access denied to student panel.');
            }

            if ($userRole == 3 && ($request->is('admin/*') || $request->is('faculty/*'))) {
                return redirect('/student/dashboard')->with('status', 'warning')->with('warning', 'Access denied to admin or faculty panel.');
            }

            if ($userRole == 4 && ($request->is('admin/*') || $request->is('faculty/*'))) {
                return redirect('/superadmin/accounts')->with('status', 'warning')->with('warning', 'Access denied to admin or faculty panel.');
            }

            if ($userRole == 4 && $request->is('student/*')) {
                return redirect('/superadmin/accounts')->with('status', 'warning')->with('warning', 'Access denied to student panel.');
            }

            if ($userRole == 1 && $request->is('faculty')) {
                return redirect('/faculty/class-record');
            }
            if ($userRole == 2 && $request->is('admin')) {
                return redirect('/admin/accounts');
            }
            if ($userRole == 3 && $request->is('student')) {
                return redirect('/student/class-record');
            }

            if ($userRole == 4 && $request->is('superadmin')) {
                return redirect('/superadmin/accounts');
            }
        }


        return $next($request);
    }
}
