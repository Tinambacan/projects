<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $csrfToken = $request->header('X-CSRF-TOKEN');
        if ($csrfToken !== csrf_token()) {
            return response()->json(['error' => 'CSRF token mismatch'], 419);
        }

        $apiKey = session('api_key');
        $validApiKey = env('API_KEY');

        if ($apiKey !== $validApiKey) {
            return response()->json(['error' => 'Unauthorized: Invalid API Key'], 401);
        }

        return $next($request);
    }
}
