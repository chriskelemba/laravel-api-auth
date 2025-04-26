<?php

namespace App\Exceptions\Custom;

use Exception;

class ServerErrorException extends BaseCustomException
{
    protected $statusCode = 500;
}
