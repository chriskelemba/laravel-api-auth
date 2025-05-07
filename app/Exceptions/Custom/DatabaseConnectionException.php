<?php

namespace App\Exceptions\Custom;

use Illuminate\Support\Facades\Log;

class DatabaseConnectionException extends BaseCustomException
{
    protected $message = 'Database connection failed';

    public function render($request)
    {
        Log::alert('Database connection error: ' . $this->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => $this->message,
        ], 500);
    }

    public static function isConnectionError(string $message): bool
    {
        $errorCodes = [
            'SQLSTATE[HY000] [2002]', // Connection refused
            'SQLSTATE[HY000] [1049]', // Unknown database
            'SQLSTATE[HY000] [1045]', // Access denied
            'SQLSTATE[HY000] [2005]', // Unknown MySQL host
        ];

        foreach ($errorCodes as $code) {
            if (str_contains($message, $code)) {
                return true;
            }
        }

        return false;
    }
}