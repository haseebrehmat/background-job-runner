<?php

namespace App\Services;

use Exception;

class ClassMethodValidator
{
    public static function validate($className, $method)
    {
        $allowedClasses = config('background_jobs.allowed_classes', []);

        if (!in_array($className, $allowedClasses, true) || !method_exists($className, $method))
        {
            throw new Exception("Invalid class or method: '{$className}::{$method}'");
        }
    }
}
