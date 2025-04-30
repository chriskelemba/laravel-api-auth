<?php

namespace App\Exceptions\Custom;


class ServerErrorException extends BaseCustomException
{
    protected $statusCode = 500;
}
