<?php

namespace Tests\Unit;

use App\Jobs\CleanupOldRecordsJob;
use App\Jobs\GenerateReportJob;
use App\Jobs\SendEmailJob;
use Tests\TestCase;

class JobsTest extends TestCase
{
    /** @test */
    public function it_executes_cleanup_old_records_job_successfully()
    {
        $job = new CleanupOldRecordsJob();
        $result = $job->execute(30);

        $this->assertEquals("Records older than 30 days cleaned up", $result);
    }

    /** @test */
    public function it_executes_generate_report_job_successfully()
    {
        $job = new GenerateReportJob();
        $result = $job->execute('Sales');

        $this->assertEquals("Sales report generated", $result);
    }

    /** @test */
    public function it_executes_send_email_job_successfully()
    {
        $job = new SendEmailJob();
        $result = $job->execute('test@example.com', 'Subject', 'Message');

        $this->assertEquals("Email sent to test@example.com", $result);
    }
}
