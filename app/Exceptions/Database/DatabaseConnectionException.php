<?php

namespace App\Exceptions\Database;

use Exception;

class DatabaseConnectionException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Service temporarily unavailable',
            'status' => 503
        ], 503);
    }
    
    public function report()
    {
        // Log the actual error internally
        \Log::error('Database connection failed: ' . $this->getMessage());
    }
}
