<?php

namespace App\Exceptions\Custom;


class ValidationException extends BaseCustomException
{
    protected $statusCode = 422;
}
