<?php

namespace App\Services;

use Exception;
use ReflectionMethod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class BackgroundJobRunner
{
    protected $retries;
    protected $allowedClasses;

    public function __construct(int $retries = 3)
    {
        $this->retries = $retries ?? Config::get('background_jobs.retries', 3);
        $this->allowedClasses = config('background_jobs.allowed_classes', []);
    }

    public function run($className, $method, $parameters = [])
    {
        try
        {
            // Validate class and method
            if (!$this->isClassAllowed($className) || !method_exists($className, $method))
            {
                throw new Exception('Invalid class or method');
            }

            // Validate parameter names
            $this->validateParameters($className, $method, $parameters);

            $attempt = 0;
            while ($attempt < $this->retries)
            {
                try
                {
                    // Instantiate and call the method
                    $instance = new $className();
                    $result = call_user_func_array([$instance, $method], $parameters);

                    // Log and return success message
                    $this->logJobStatus($className, $method, 'success');
                    return ['type' => "success", 'message' => "Job successfully completed for {$className}::{$method}."];
                } catch (Exception $e)
                {
                    $attempt++;
                    // Log each retry attempt with delay
                    $this->logJobStatus($className, $method, 'retry', "Attempt {$attempt} failed: {$e->getMessage()}");
                    if ($attempt >= $this->retries)
                    {
                        throw new Exception("Job failed after {$this->retries} attempts for {$className}::{$method}. Error: {$e->getMessage()}");
                    }
                }
            }
        } catch (Exception $e)
        {
            // Log and return failure message
            $this->logJobStatus($className, $method, 'failed', $e->getMessage());
            return ['type' => "error", 'message' => "Job failed for {$className}::{$method} with error: {$e->getMessage()}"];
        }
    }

    protected function validateParameters($className, $method, $parameters)
    {
        $reflection = new ReflectionMethod($className, $method);
        $expectedParams = $reflection->getParameters();

        foreach ($expectedParams as $param)
        {
            $paramName = $param->getName();
            if (!array_key_exists($paramName, $parameters))
            {
                throw new Exception("Missing required parameter: '{$paramName}' for method '{$className}::{$method}'");
            }
        }
    }

    protected function logJobStatus($className, $method, $status, $errorMessage = null)
    {
        $logMessage = [
            'class'     => $className,
            'method'    => $method,
            'status'    => $status,
            'error'     => $errorMessage,
            'timestamp' => now()->toDateTimeString(),
        ];

        $logChannel = Log::channel('background_jobs');

        if ($status === 'success')
        {
            $logChannel->info($logMessage);
        } else
        {
            $logChannel->error($logMessage);
        }
    }

    protected function isClassAllowed($class)
    {
        return in_array($class, $this->allowedClasses, true);
    }
}
