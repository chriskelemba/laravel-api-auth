<?php

namespace App\Exceptions\Custom;

use Illuminate\Support\Facades\Log;

class DatabaseErrorException extends BaseCustomException
{
    protected $message = 'Critical database error occurred';

    public function render($request)
    {
        Log::critical('Database error: ' . $this->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => $this->message,
        ], 500);
    }
}