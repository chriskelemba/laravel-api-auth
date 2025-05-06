<?php

namespace App\Exceptions\Custom\Database;

use Illuminate\Database\QueryException as BaseQueryException;
use Throwable;

class CustomQueryException extends BaseQueryException
{
    protected function formatMessage($connectionName, $sql, $bindings, Throwable $previous)
    {
        return app()->isProduction()
            ? 'Database service unavailable. Our team has been notified.'
            : parent::formatMessage($connectionName, $sql, $bindings, $previous);
    }
}
