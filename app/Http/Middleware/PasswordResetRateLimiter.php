<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Create a unique key for this IP address
        $key = 'password-reset:' . $request->ip();
        
        // Check if the user has exceeded the maximum attempts (3 per hour)
        if (RateLimiter::tooManyAttempts($key, 3)) {
            // Get the number of seconds until the user can try again
            $seconds = RateLimiter::availableIn($key);
            
            // Return a response with retry information
            return response()->json([
                'message' => 'Too many password reset attempts. Please try again later.',
                'retry_after' => now()->addSeconds($seconds)->format('Y-m-d H:i:s'),
                'seconds_remaining' => $seconds
            ], 429);
        }
        
        // Add a hit to the rate limiter
        RateLimiter::hit($key, 20 * 20); // Keep the key in storage for 1 hour
        
        // Continue with the request
        return $next($request);
    }
}