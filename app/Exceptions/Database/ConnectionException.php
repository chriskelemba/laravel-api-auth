<?php

namespace App\Exceptions\Database;

use Exception;
use Illuminate\Support\Facades\Log;

class ConnectionException extends Exception
{
    protected $logLevel = 'ALERT';
    
    public function report()
    {
        Log::alert('Database Connection Error: ' . $this->getMessage(), [
            'exception' => $this,
            'trace' => $this->getTrace()
        ]);
    }
}
