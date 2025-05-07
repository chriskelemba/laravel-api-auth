<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Events\SecurityAlertEvent;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 15)
    {
        $key = 'login_attempts:'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return response()->json([
                'message' => 'Too many login attempts. Please try again in '.ceil($retryAfter / 60).' minutes.'
            ], 429);
        }
        
        $response = $next($request);
        
        if ($response->status() === Response::HTTP_UNAUTHORIZED) {
            RateLimiter::hit($key, $decayMinutes * 60);
            
            $attempts = RateLimiter::attempts($key);
            
            // Trigger email only after 3 failed attempts
            if ($attempts === 3) {
                event(new SecurityAlertEvent(
                    'failed_login_attempt',
                    'Multiple failed login attempts detected for your account',
                    [
                        'ip' => $request->ip(),
                        'userAgent' => $request->userAgent(),
                        'attempts' => $attempts,
                        'email' => $request->email,
                        'timestamp' => now()->toDateTimeString(),
                    ],
                    User::where('email', $request->email)->first()?->id
                ));
            }
            
            if ($attempts >= $maxAttempts) {
                return response()->json([
                    'message' => 'Too many login attempts. Please try again later.'
                ], 429);
            }
        }
        
        return $response;
    }
}