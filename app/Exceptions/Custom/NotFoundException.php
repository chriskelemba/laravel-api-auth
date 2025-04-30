<?php
namespace App\Exceptions\Custom;

class NotFoundException extends BaseCustomException
{
    protected $statusCode = 404;

    public function __construct(string $model = null)
    {
        // Custom message depending on model (eg. User, Role and Permissions model)
        $message = $model 
            ? "$model not found."
            : "Resource not found.";

        parent::__construct($message);
    }
}