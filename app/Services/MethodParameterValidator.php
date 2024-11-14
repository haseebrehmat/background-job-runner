<?php

namespace App\Services;

use Exception;
use ReflectionMethod;

class MethodParameterValidator
{
    public static function validate($className, $method, $parameters)
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
}
