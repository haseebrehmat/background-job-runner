<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class SendEmailJob
{
    public function execute($email, $subject, $message)
    {
        // Simulate email sending
        Log::channel('background_jobs')->info("Sending email to {$email} with subject '{$subject}' and message '{$message}'");

        // Log success
        return "Email sent to {$email}";
    }
}
