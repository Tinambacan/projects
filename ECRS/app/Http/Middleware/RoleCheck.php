<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (session()->has('role') && in_array(session('role'), $roles)) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}

