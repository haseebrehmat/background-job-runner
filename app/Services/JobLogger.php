<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class JobLogger
{
    public static function logStatus($className, $method, $status, $errorMessage = null)
    {
        $logMessage = [
            'class'     => $className,
            'method'    => $method,
            'status'    => $status,
            'error'     => $errorMessage,
            'timestamp' => now()->toDateTimeString(),
        ];

        $logChannel = Log::channel('background_jobs');

        if ($status === 'success')
        {
            $logChannel->info($logMessage);
        } else
        {
            $logChannel->error($logMessage);
        }
    }
}
