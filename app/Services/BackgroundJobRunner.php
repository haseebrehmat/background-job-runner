<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Config;

class BackgroundJobRunner
{
    protected $retries;

    public function __construct(int $retries = 3)
    {
        $this->retries = $retries ?? Config::get('background_jobs.retries', 3);
    }

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
    }
}
