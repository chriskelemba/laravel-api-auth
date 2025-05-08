<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\BaseCustomException;

class UserAuthenticationException extends BaseCustomException
{
    public function __construct(string $message = 'Invalid Credentials', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}