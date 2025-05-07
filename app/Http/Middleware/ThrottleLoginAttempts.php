<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Exceptions\Custom\UserAuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 15)
    {
        $key = 'login_attempts:'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            throw new UserAuthenticationException(
                'Too many login attempts. Please try again in '.ceil($retryAfter / 60).' minutes.'
            );
        }
        
        $response = $next($request);
        
        if ($response->status() === Response::HTTP_UNAUTHORIZED) {
            RateLimiter::hit($key, $decayMinutes * 60);
            
            $remaining = $maxAttempts - RateLimiter::attempts($key);
            
            if ($remaining <= 3) {
                throw new UserAuthenticationException(
                    'Invalid credentials. You have '.$remaining.' attempts remaining.'
                );
            }
        }
        
        return $response;
    }
}