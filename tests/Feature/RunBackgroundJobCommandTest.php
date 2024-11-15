<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RunBackgroundJobCommandTest extends TestCase
{
    /** @test */
    public function it_executes_run_background_job_command_successfully()
    {
        // Spy on the Artisan facade to capture command calls
        Artisan::spy();

        // Call the command with specific parameters
        Artisan::call('job:run', [
            'class'      => 'App\Jobs\CleanupOldRecordsJob',
            'method'     => 'execute',
            'parameters' => json_encode(['days' => 30]),
        ]);

        // Verify that the command was called with the correct parameters
        Artisan::shouldHaveReceived('call')
            ->with('job:run', [
                'class'      => 'App\Jobs\CleanupOldRecordsJob',
                'method'     => 'execute',
                'parameters' => json_encode(['days' => 30]),
            ])
            ->once();
    }
}
