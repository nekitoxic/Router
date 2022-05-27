<?php

namespace App\Router;

use ReflectionClass;
use ReflectionMethod;

class DIService
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

    public static function methodResolve(ReflectionMethod $method, string $url): array
    {
        $arguments = [];
        $keyValues = URLService::getKeyValuesFromUrl($url, $method->getAttributes(Endpoint::class));

        var_dump($keyValues);

        foreach ($method->getParameters() as $param) {
            $paramTypeName = $param->getType()->getName();

            if (class_exists($paramTypeName)) {
                //@TODO Проверять класс на принадлежность родителя Entity|Model
                //@TODO И вытягивать нужный объект из БД

                $arguments[] = self::classResolve($paramTypeName);
                continue;
            }

        //     if ($param->isOptional() || $param->isDefaultValueAvailable()) {
        //         $arguments[] = $param->getDefaultValue();
        //     }
        }

        return $arguments;
    }
}