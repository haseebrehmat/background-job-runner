<?php

use App\Services\BackgroundJobRunner;

if (!function_exists('runBackgroundJob'))
{
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

    function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    function executeWindowsJob($class, $method, $encodedParams)
    {
        pclose(popen("start /B php artisan job:run \"$class\" \"$method\" $encodedParams", "r"));
    }

    function executeUnixJob($class, $method, $encodedParams)
    {
        shell_exec("php artisan job:run \"$class\" \"$method\" $encodedParams > /dev/null 2>/dev/null &");
    }
}
