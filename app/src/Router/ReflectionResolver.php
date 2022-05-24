<?php

namespace App\Router;

use ReflectionClass;
use ReflectionMethod;
use stdClass;

class ReflectionResolver
{
    public static function classResolve(string $class): mixed
    {
        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor || empty($constructor->getParameters())) {
            return $reflectionClass->newInstance();
        }

        $newInstanceParams = [];

        foreach ($constructor->getParameters() as $param) {
            $paramType              = $param->getType();
            $newInstanceParams[]    = (null === $paramType) ? $param->getDefaultValue() : self::classResolve($paramType->getName());
        }

        return $reflectionClass->newInstanceArgs($newInstanceParams);
    }

    public static function methodResolve(ReflectionMethod $method): array
    {
        $arguments = [];

        foreach ($method->getParameters() as $param) {
            $paramTypeName = $param->getType()->getName();
            
            if (class_exists($paramTypeName)) {
                $arguments[] = self::classResolve($paramTypeName);
                continue;
            }

            // разобраться с дефолтными данными
            $arguments[] = $param->getDefaultValue();
        }

        return $arguments;
    }
}