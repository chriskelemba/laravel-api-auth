<?php

namespace App\Exceptions\Custom;

class EmailAlreadyVerifiedException extends BaseCustomException
{
    protected $message = 'Email already verified';
    protected $statusCode = 400;
}