<?php

use App\Services\BackgroundJobRunner;

if (!function_exists('runBackgroundJob'))
{
    /**
     * Run a background job on the specified class and method with parameters.
     *
     * This function uses the BackgroundJobRunner service to execute a job in the
     * background. It will automatically determine if the system is running on
     * Windows or a Unix-based system and execute the job accordingly.
     *
     * @param string $class The class name of the job to run.
     * @param string $method The method name of the job to run.
     * @param array $params The parameters to pass to the job method.
     */
    function runBackgroundJob($class, $method, $params = [])
    {
        $runner = new BackgroundJobRunner();

        // Serialize parameters to JSON format for safe shell execution
        $encodedParams = escapeshellarg(json_encode($params));

        // Determine platform and execute accordingly
        if (isWindows())
        {
            executeWindowsJob($class, $method, $encodedParams);
        } else
        {
            executeUnixJob($class, $method, $encodedParams);
        }
    }

    /**
     * Determine if the OS is Windows
     *
     * @return bool
     */
    function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * Execute job in Windows environment
     *
     * @param string $class
     * @param string $method
     * @param string $encodedParams
     */
    function executeWindowsJob($class, $method, $encodedParams)
    {
        pclose(popen("start /B php artisan job:run \"$class\" \"$method\" $encodedParams", "r"));
    }

    /**
     * Execute job in Unix-based environment
     *
     * @param string $class
     * @param string $method
     * @param string $encodedParams
     */
    function executeUnixJob($class, $method, $encodedParams)
    {
        shell_exec("php artisan job:run \"$class\" \"$method\" $encodedParams > /dev/null 2>/dev/null &");
    }
}
