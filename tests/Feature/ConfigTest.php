<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_checks_background_jobs_config()
    {
        $retries = config('background_jobs.retries');
        $this->assertTrue(is_int($retries) || is_string($retries), 'The retries configuration should be an integer or a string.');
        $this->assertIsArray(config('background_jobs.allowed_classes'));
    }

    /** @test */
    public function it_checks_logging_config_for_background_jobs()
    {
        $loggingConfig = config('logging.channels.background_jobs');
        $this->assertEquals('single', $loggingConfig['driver']);
        $this->assertStringContainsString('logs/background_jobs.log', $loggingConfig['path']);
        $this->assertEquals('info', $loggingConfig['level']);
    }
}
