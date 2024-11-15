<?php

namespace Tests\Unit;

use App\Services\BackgroundJobRunner;
use App\Services\ClassMethodValidator;
use App\Services\JobLogger;
use App\Services\MethodParameterValidator;
use App\Jobs\CleanupOldRecordsJob;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Exception;

class ServicesTest extends TestCase
{
    /** @test */
    public function it_runs_a_job_successfully()
    {
        $runner = new BackgroundJobRunner();
        $result = $runner->run(CleanupOldRecordsJob::class, 'execute', ['days' => 30]);

        $this->assertEquals('success', $result['type']);
    }

    /** @test */
    public function it_fails_to_run_a_job_with_incorrect_parameter()
    {
        $runner = new BackgroundJobRunner();
        $result = $runner->run(CleanupOldRecordsJob::class, 'execute', ['invalidParam' => 30]);

        $this->assertEquals('error', $result['type']);
        $this->assertStringContainsString('Missing required parameter', $result['message']);
    }

    /** @test */
    public function it_validates_a_valid_class_and_method()
    {
        $this->assertNull(
            ClassMethodValidator::validate(CleanupOldRecordsJob::class, 'execute', [CleanupOldRecordsJob::class])
        );
    }

    /** @test */
    public function it_throws_an_exception_for_an_invalid_method()
    {
        $this->expectException(Exception::class);

        ClassMethodValidator::validate(CleanupOldRecordsJob::class, 'nonExistentMethod', [CleanupOldRecordsJob::class]);
    }

    /** @test */
    public function it_validates_parameters_successfully()
    {
        $this->assertNull(
            MethodParameterValidator::validate(CleanupOldRecordsJob::class, 'execute', ['days' => 30])
        );
    }

    /** @test */
    public function it_throws_exception_for_missing_parameters()
    {
        $this->expectException(Exception::class);

        MethodParameterValidator::validate(CleanupOldRecordsJob::class, 'execute', ['invalidParam' => 30]);
    }
}
