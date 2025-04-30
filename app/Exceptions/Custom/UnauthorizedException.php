<?php

namespace App\Exceptions\Custom;


class UnauthorizedException extends BaseCustomException
{
    protected $statusCode = 401;
}