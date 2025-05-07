<?php

namespace App\Exceptions\Custom;

use Exception;
use Illuminate\Http\Request;

abstract class BaseCustomException extends Exception
{
    protected $statusCode = 500;
    protected $success = false;

    public function __construct(string $message = '', int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
    }

    public function render(Request $request)
    {
        return response()->json([
            'success' => $this->success,
            'message' => $this->getMessage(),
            'status' => (string) $this->getStatusCode()
        ], $this->getStatusCode());
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}