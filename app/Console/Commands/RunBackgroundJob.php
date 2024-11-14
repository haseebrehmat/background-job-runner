<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackgroundJobRunner;

class RunBackgroundJob extends Command
{
    protected $signature = 'job:run {class} {method} {parameters?}';
    protected $description = 'Run a background job';

    /**
     * Handle the execution of a background job.
     *
     * This method retrieves the class name, method name, and parameters from the
     * command line arguments, and uses the BackgroundJobRunner service to execute
     * the specified method. It outputs the result of the execution to the command line.
     *
     * If the job execution fails, an error message is displayed.
     */
    public function handle()
    {
        $className = $this->argument('class');
        $method = $this->argument('method');
        $parameters = json_decode($this->argument('parameters'), true) ?? [];

        $runner = new BackgroundJobRunner();
        $result = $runner->run($className, $method, $parameters);

        if ($result['type'] === 'error') {
            $this->error($result['message']);
            return;
        }

        $this->info($result['message']); // Output the result to the command line
    }
}
