<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRateLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'password-reset:' . ($request->email ?? $request->ip());
        $maxAttempts = 3;
        $decaySeconds = 180; // 3 minutes cooldown

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);

            return response()->json([
                'success' => false,
                'message' => "Please wait {$minutes} minute(s) before requesting another password reset. Check your email for the previous reset link.",
                'retry_after' => now()->addSeconds($seconds)->toIso8601String(),
                'seconds_remaining' => $seconds,
                'status' => 429
            ], 429);
        }

        RateLimiter::hit($key, $decaySeconds);
        return $next($request);
    }
}