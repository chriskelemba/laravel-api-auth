<?php

namespace App\Exceptions\Custom;

use Exception;

class UserBlockedException extends BaseCustomException
{
    public function __construct(string $message = 'Your account is blocked due to multiple failed login attempts. Please contact support.', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
