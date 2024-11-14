<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

/**
 * Job class responsible for sending an email to the given recipient with the given subject and message.
 */
class SendEmailJob
{
    /**
     * Send an email to the given recipient with the given subject and message.
     *
     * @param string $email The recipient of the email.
     * @param string $subject The subject of the email.
     * @param string $message The message to be sent in the email.
     * @return string A message indicating the email was sent.
     */
    public function execute($email, $subject, $message)
    {
        // Simulate email sending
        Log::channel('background_jobs')->info("Sending email to {$email} with subject '{$subject}' and message '{$message}'");

        // Log success
        return "Email sent to {$email}";
    }
}
