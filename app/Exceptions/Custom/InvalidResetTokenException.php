<?php

namespace App\Exceptions\Custom;

use Exception;

class InvalidResetTokenException extends Exception
{
    protected $statusCode = 400;
    
    public function __construct($message = 'Invalid or expired reset token')
    {
        parent::__construct($message);
    }
}
