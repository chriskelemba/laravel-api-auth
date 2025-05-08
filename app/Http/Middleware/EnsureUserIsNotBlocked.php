<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\UserBlockedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user()->fresh(); // Get fresh data from database

            if ($user->blocked === 'Y') {
                throw new UserBlockedException(
                    'Your account is blocked due to multiple failed login attempts. Please contact support.'
                );
            }
        }

        return $next($request);
    }
}