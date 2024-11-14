<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class CleanupOldRecordsJob
{
    public function execute($days)
    {
        // Simulate cleanup operation
        Log::channel('background_jobs')->info("Cleaning up records older than {$days} days.");

        // Log success
        return "Records older than {$days} days cleaned up";
    }
}
