<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

/**
 * Job class responsible for cleaning up old records from the database.
 *
 * This job takes a single parameter, `$days`, which determines how old a record must be to be considered for cleanup.
 */
class CleanupOldRecordsJob
{
    /**
     * Execute the job to clean up records older than the specified number of days.
     *
     * @param int $days The number of days to determine which records to clean up.
     * @return string A message indicating the cleanup operation was successful.
     */
    public function execute($days)
    {
        // Simulate cleanup operation
        Log::channel('background_jobs')->info("Cleaning up records older than {$days} days.");

        // Log success
        return "Records older than {$days} days cleaned up";
    }
}
