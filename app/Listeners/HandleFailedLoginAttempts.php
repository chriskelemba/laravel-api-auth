<?php

namespace App\Listeners;

use App\Events\UserLoginFailed;
use App\Mail\SecurityTeamAlert;
use App\Mail\UserBlockedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class HandleFailedLoginAttempts
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoginFailed $event): void
    {
        \Log::info('Handling failed login for user: ' . $event->user->id);

        $user = $event->user;
        $user->increment('login_attempts');

        \Log::info('Current attempts: ' . $user->login_attempts);

        if ($user->login_attempts >= 5 && !$user->blocked) {
            $user->update(['blocked' => 'Y']);
            \Log::info('User blocked: ' . $user->id);

            Mail::to($user->email)->queue(new UserBlockedNotification($user));
            Mail::to('premtube822@gmail.com')->queue(new SecurityTeamAlert($user));
        }
    }
}
