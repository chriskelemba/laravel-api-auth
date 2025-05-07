<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\BaseCustomException;
use Illuminate\Support\Facades\Log;

class UserAuthenticationException extends BaseCustomException
{
    public function __construct(string $message = 'Authentication failed', int $code = 401)
    {
        parent::__construct($message, $code);
        
        // Log the exception with NOTICE level
        Log::notice($message, [
            'exception' => $this,
            'user_id' => auth()->id() ?? 'guest',
            'ip' => request()->ip()
        ]);
        
        // Trigger in-app alert and email
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
            ];
            
            // Send in-app notification
            if ($user) {
                $user->notify(new \App\Notifications\SecurityAlert(
                    'Authentication Exception',
                    $this->getMessage(),
                    $data
                ));
            }
            
            // Email security team
            \Mail::to('premtube822@gmail.com')->send(
                new \App\Mail\SecurityAlertMail(
                    'Authentication Exception',
                    $this->getMessage(),
                    $data
                )
            );
            
        } catch (\Exception $e) {
            Log::error('Failed to send authentication exception alerts: ' . $e->getMessage());
        }
    }
}