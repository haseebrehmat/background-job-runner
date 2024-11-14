<?php

namespace App\Services;

use Exception;

/**
 * Validates if a class and method are valid to be executed in the background.
 *
 * This class is responsible for validating if a class and method are allowed
 * to be executed in the background. It verifies if the class and method exist,
 * and if the class is in the list of allowed classes defined in the
 * 'background_jobs.allowed_classes' configuration value.
 *
 * @see config/background_jobs.php
 */
class ClassMethodValidator
{
    /**
     * Validate if a class and method are valid to be executed in the background.
     *
     * @param string $className The class name to validate.
     * @param string $method The method name to validate.
     *
     * @throws Exception If the class or method is not valid.
     */
    public static function validate($className, $method)
    {
        $allowedClasses = config('background_jobs.allowed_classes', []);

        if (!in_array($className, $allowedClasses, true) || !method_exists($className, $method))
        {
            throw new Exception("Invalid class or method: '{$className}::{$method}'");
        }
    }
}

