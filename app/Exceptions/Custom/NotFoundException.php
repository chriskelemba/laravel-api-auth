<?php

namespace App\Exceptions\Custom;

class NotFoundException extends BaseCustomException
{
    protected $statusCode = 404;
}

