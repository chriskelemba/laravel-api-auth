<?php

namespace App\Exceptions\Custom;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\QueryExceptionAlert;

class DatabaseQueryException extends BaseCustomException
{
    protected $message = 'Database query error occurred';

    public function render($request)
    {
        Log::error('Database query error: ' . $this->getMessage());

        // Notification::sendQueryExceptionAlert($this, $request->fullUrl(), $request->all());
        Notification::route('mail', 'clxshy@gmail.com')->notify(
            new QueryExceptionAlert($this, $request->fullUrl(), $request->all())
        );        
        
        return response()->json([
            'success' => false,
            'message' => $this->message,
        ], 500);
    }

    public static function isQueryError(string $message): bool
    {
        $errorCodes = [
            'SQLSTATE[42S02]', // Base table or view not found
            'SQLSTATE[42S22]', // Column not found
            'SQLSTATE[23000]', // Integrity constraint violation
            'SQLSTATE[42000]', // Syntax error or access violation
        ];

        foreach ($errorCodes as $code) {
            if (str_contains($message, $code)) {
                return true;
            }
        }

        return false;
    }
}