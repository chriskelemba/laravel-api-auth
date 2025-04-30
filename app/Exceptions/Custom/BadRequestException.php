<?php

namespace App\Exceptions\Custom;

class BadRequestException extends BaseCustomException
{
    protected $statusCode = 400;
}
