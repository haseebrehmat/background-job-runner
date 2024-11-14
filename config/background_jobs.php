<?php

return [
    'allowed_classes' => [
        \App\Jobs\SendEmailJob::class,
        \App\Jobs\GenerateReportJob::class,
        \App\Jobs\CleanupOldRecordsJob::class,
    ],
    'retries'         => env('BACKGROUND_JOB_MAX_RETRIES', 3),
];
