<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class GenerateReportJob
{
    public function execute($reportType)
    {
        // Simulate report generation
        Log::channel('background_jobs')->info("Generating a {$reportType} report.");

        // Log success
        return "{$reportType} report generated";
    }
}
