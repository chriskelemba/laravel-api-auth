<?php

namespace App\Exceptions\Custom\Database;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Psr\Log\LogLevel;

class ConnectionException extends Exception
{
    public function __construct(string $message = 'Database connection failed', int $code = 503, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => 0,
                'message' => $this->getMessage(),
                'status' => (string) $this->getCode(),
            ], $this->getCode());
        }

        // For web requests
        return response()->view('errors.connection', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }

    public function report()
    {
        // Custom reporting logic can go here
        Log::log(LogLevel::ALERT, $this->getMessage(), [
            'exception' => $this,
            'code' => $this->getCode(),
        ]);
    }
}
