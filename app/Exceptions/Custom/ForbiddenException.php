<?php

namespace App\Exceptions\Custom;

class ForbiddenException extends BaseCustomException
{
    protected $statusCode = 403;
}
