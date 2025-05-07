<?php

namespace App\Listeners;

use App\Events\SecurityAlertEvent;
use App\Mail\SecurityAlertMail;
use App\Models\User;
use App\Notifications\SecurityAlert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;

class HandleSecurityAlert implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(SecurityAlertEvent $event)
    {
        // Send email to security team
        Mail::to('premtube822@gmail.com')
            ->queue(new SecurityAlertMail(
                $event->type,
                $event->message,
                $event->context
            ));

            if ($event->userId) {
                $user = User::find($event->userId);
                
                // Only send email for important events
                if (in_array($event->type, ['failed_login_attempt', 'suspicious_activity'])) {
                    $user->notify(new SecurityAlert(
                        $event->type,
                        $event->message,
                        array_merge($event->context, [
                            'attempts_remaining' => 5 - RateLimiter::attempts('login_attempts:'.request()->ip())
                        ])
                    ));
                }
            }
    }
}
