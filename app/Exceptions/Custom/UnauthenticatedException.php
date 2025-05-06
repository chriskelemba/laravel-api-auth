<?php

namespace App\Exceptions\Custom;


class UnauthenticatedException extends BaseCustomException
{
    protected $statusCode = 401;

    protected $message = 'Invalid Credentials';
}