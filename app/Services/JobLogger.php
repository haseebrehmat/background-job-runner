<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Class responsible for logging the status of background job executions.
 *
 * This class has a single static method, {@see logStatus}, which logs the status of a job execution.
 * The impact of this class is that it provides a clear log of job executions and their results.
 */
class JobLogger
{
    /**
     * Log the status of a job execution.
     *
     * @param string $className The name of the class where the job is executed.
     * @param string $method The method name of the job.
     * @param string $status The status of the job execution ('success' or 'failed').
     * @param string|null $errorMessage Optional error message if the job failed.
     */
    public static function logStatus($className, $method, $status, $errorMessage = null)
    {
        // Create a log message containing job details and timestamp
        $logMessage = [
            'class'     => $className,
            'method'    => $method,
            'status'    => $status,
            'error'     => $errorMessage,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Get the logging channel for background jobs
        $logChannel = Log::channel('background_jobs');

        // Log the message with appropriate level based on status
        if ($status === 'success')
        {
            $logChannel->info($logMessage);
        } else
        {
            $logChannel->error($logMessage);
        }
    }
}

