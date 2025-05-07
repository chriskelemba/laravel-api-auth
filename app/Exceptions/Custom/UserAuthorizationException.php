<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\BaseCustomException;
use Illuminate\Support\Facades\Log;

class UserAuthorizationException extends BaseCustomException
{
    public function __construct(string $message = 'Authorization failed', int $code = 403)
    {
        parent::__construct($message, $code);

        // Log the exception with WARNING level
        Log::warning($message, [
            'exception' => $this,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'route' => request()->path()
        ]);

        // Trigger in-app notification and security dashboard alert
        $this->triggerAlert();
    }

    protected function triggerAlert()
    {
        try {
            $user = auth()->user();
            $data = [
                'ip' => request()->ip(),
                'userAgent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
                'attempted_route' => request()->path(),
                'method' => request()->method(),
            ];

            // Send in-app notification to user
            if ($user) {
                $user->notify->queue(new \App\Notifications\SecurityAlert(
                    'Authorization Exception',
                    $this->getMessage(),
                    $data
                ));
            }

            // Log to Laravel's logging system with a special channel
            Log::channel('security')->warning('Authorization Exception', [
                'user_id' => $user ? $user->id : null,
                'exception' => $this->getMessage(),
                'context' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process authorization exception alerts: ' . $e->getMessage());
        }
    }
}