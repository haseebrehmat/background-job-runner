<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Config;

/**
 * Class responsible for running jobs in the background independently of the
 * Laravel queue system.
 *
 * This class is different from the Laravel queue system in that it runs
 * jobs immediately without the need for a worker process. It also does not
 * provide the same level of retryable job functionality as the Laravel
 * queue system.
 */
class BackgroundJobRunner
{
    protected $retries;

    /**
     * Construct a new instance with the given number of retries.
     *
     * @param int $retries The number of times to retry a job if it fails.
     */
    public function __construct(int $retries = 3)
    {
        $this->retries = $retries ?? Config::get('background_jobs.retries', 3);
    }

    /**
     * Run the given class and method as a background job.
     *
     * @param string $className The name of the class to run the method in.
     * @param string $method The name of the method to run.
     * @param array $parameters An array of parameters to pass to the method.
     *
     * @return array An array with a 'type' key of 'success' or 'error', and a 'message' key with a message about the job.
     */
    public function run($className, $method, $parameters = [])
    {
        try
        {
            // Validate class and method
            ClassMethodValidator::validate($className, $method);

            // Validate parameter names
            MethodParameterValidator::validate($className, $method, $parameters);

            $attempt = 0;
            while ($attempt < $this->retries)
            {
                try
                {
                    // Instantiate and call the method
                    $instance = new $className();
                    call_user_func_array([$instance, $method], $parameters);

                    // Log and return success message
                    JobLogger::logStatus($className, $method, 'success');
                    return ['type' => "success", 'message' => "Job successfully completed for {$className}::{$method}."];

                } catch (Exception $e)
                {
                    $attempt++;
                    // Log each retry attempt with delay
                    JobLogger::logStatus($className, $method, 'retry', "Attempt {$attempt} failed: {$e->getMessage()}");
                    if ($attempt >= $this->retries)
                    {
                        throw new Exception("Job failed after {$this->retries} attempts for {$className}::{$method}. Error: {$e->getMessage()}");
                    }
                }
            }
        } catch (Exception $e)
        {
            // Log and return failure message
            JobLogger::logStatus($className, $method, 'failed', $e->getMessage());
            return ['type' => "error", 'message' => "Job failed for {$className}::{$method} with error: {$e->getMessage()}"];
        }
        return ['type' => "error", 'message' => "An unexpected error occurred."];
    }
}

