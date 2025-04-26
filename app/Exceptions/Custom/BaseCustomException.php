<?php

namespace App\Exceptions\Custom;

use Exception;

abstract class BaseCustomException extends Exception
{
    protected $statusCode = 500;
    protected $success = false;

    public function render($request)
    {
        return response()->json([
            'success' => $this->success,
            'message' => $this->getMessage(),
            'status' => (string)  $this->statusCode
        ]);
    }
}
