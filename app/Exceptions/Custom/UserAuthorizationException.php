<?php

namespace App\Exceptions\Custom;

use App\Exceptions\Custom\BaseCustomException;

class UserAuthorizationException extends BaseCustomException
{
    public function __construct(string $message = 'Authorization failed', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}