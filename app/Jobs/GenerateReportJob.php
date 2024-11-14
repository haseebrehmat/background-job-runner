<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

/**
 * A job class responsible for simulating the generation of reports.
 */
class GenerateReportJob
{
    /**
     * Simulate generating a report of the given type and log its success.
     *
     * @param string $reportType The type of report to generate.
     * @return string A message indicating the report was generated.
     */
    public function execute($reportType)
    {
        // Simulate report generation
        Log::channel('background_jobs')->info("Generating a {$reportType} report.");

        // Log success
        return "{$reportType} report generated";
    }
}
