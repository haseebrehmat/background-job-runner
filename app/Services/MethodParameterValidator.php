<?php

namespace App\Services;

use Exception;
use ReflectionMethod;

/**
 * Class responsible for validating if the provided parameters match the expected parameters
 * of the given class method.
 *
 * This class uses the ReflectionMethod class, which is a part of PHP's Reflection API.
 * ReflectionMethod is used to introspect a method and retrieve its parameters.
 *
 * @link https://www.php.net/manual/en/class.reflectionmethod.php
 */
class MethodParameterValidator
{
    /**
     * Validate if the provided parameters match the expected parameters
     * of the given class method.
     *
     * @param string $className The name of the class.
     * @param string $method The method to validate.
     * @param array $parameters The parameters to validate against the method's expected parameters.
     *
     * @throws Exception If any required parameter is missing.
     */
    public static function validate($className, $method, $parameters)
    {
        // Reflect the method to get its parameters
        $reflection = new ReflectionMethod($className, $method);
        $expectedParams = $reflection->getParameters();

        // Iterate over expected parameters and check their presence in the provided parameters
        foreach ($expectedParams as $param)
        {
            $paramName = $param->getName();
            if (!array_key_exists($paramName, $parameters))
            {
                throw new Exception("Missing required parameter: '{$paramName}' for method '{$className}::{$method}'");
            }
        }
    }
}

