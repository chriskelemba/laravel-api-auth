<?php

namespace App\Exceptions\Custom;

use Exception;

class UnauthorizedException extends BaseCustomException
{
    protected $statusCode = 401;

    protected $message = 'Invalid Credentials';
}