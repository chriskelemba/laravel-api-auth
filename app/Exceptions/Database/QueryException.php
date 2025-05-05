<?php

namespace App\Exceptions\Database;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException as LaravelQueryException;

class QueryException extends LaravelQueryException
{
    protected $logLevel = 'ERROR';
    
    public function report()
    {
        Log::error('Database Query Error', [
            'message' => $this->getMessage(),
            'sql' => $this->getSql(),
            'bindings' => $this->getBindings(),
            'trace' => $this->getTrace()
        ]);
    }
    
    public function getSql()
    {
        return $this->sql ?? null;
    }
}
