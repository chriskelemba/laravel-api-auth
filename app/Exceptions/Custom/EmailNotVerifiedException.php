<?php


namespace App\Exceptions\Custom;

class EmailNotVerifiedException extends BaseCustomException
{
    protected $message = 'Please verify your email address before logging in.';
    protected $statusCode = 403;
}