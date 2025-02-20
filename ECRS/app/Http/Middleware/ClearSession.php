<?php

namespace App\Http\Middleware;

use App\Models\Login;
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
        if (session()->has('loginID')) {
            $sessionId = session()->get('loginID');
            $role = session()->get('role');

            $user = Login::where('loginID', $sessionId)->first();

            if ($user) {
                switch ($user->role) {
                    case 2:
                        $redirectUrl = 'admin';
                        break;
                    case 4:
                        $redirectUrl = 'admin';
                        break;

                    default:
                        $registration = $user->registration;
                        if ($registration) {
                            switch ($registration->role) {
                                case 1:
                                    $redirectUrl = 'faculty';
                                    break;
                                case 3:
                                    $redirectUrl = 'student';
                                    break;
                                default:
                                    $redirectUrl = '/';
                                    break;
                            }
                        } elseif ($role == 2) {
                            $redirectUrl = 'admin';
                        } elseif ($role == 4) {
                            $redirectUrl = 'admin';
                        } else {
                            $redirectUrl = '/';
                        }
                        break;
                }

                session()->flush();

                return redirect($redirectUrl);
            } else {
                return redirect('/')->with([
                    'status' => 'error',
                    'message' => 'User not found!'
                ]);
            }
        }


        return $next($request);
    }
}
