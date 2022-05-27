<?php

namespace App\Router;

use App\Router\Helpers\RouterStringHelper;
use App\Router\Service\CoreFileService;
use App\Router\Service\URLService;
use ReflectionClass;
use ReflectionMethod;
use ReflectionAttribute;

class Router
{
    private string $url;

    private ?ReflectionClass $class = null;

    private ?ReflectionMethod $method = null;

    public function __construct(string $url= '')
    {
        $this->url = $url;
        $this->setData();
    }

    public function runMethod(): void
    {
        if (null === $this->method || null === $this->class) {
            return;
        }

        $agrs = [];

        $urlParams = URLService::getKeyValuesFromUrl($this->url, $this->getAttributeByUrl(
            $this->method->getAttributes(Endpoint::class))->newInstance()->getParams()['url']
        );

        foreach ($this->method->getParameters() as $param) {
            $varName = (string) $param->name;
            $typeName = $param->getType()->getName();

            if (class_exists($typeName)) {
                $agrs[] = self::classResolve($typeName);

                continue;
            }

            if (isset($urlParams[$varName])) {
                settype($urlParams[$varName], $typeName);
                $agrs[] = $urlParams[$varName]; 
            }
        }

        $this->method->invokeArgs(self::classResolve($this->class->getName()) ,$agrs);
    }

    private function setData(): void
    {
        foreach (RouterStringHelper::pathToNamespace(CoreFileService::getControllersFile()) as $class) {
            $class = new ReflectionClass($class);

            foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if (null !== $this->getAttributeByUrl($method->getAttributes(Endpoint::class))) {
                    $this->class = $class;
                    $this->method = $method;

                    return;
                }
            }
        }
    }

    private function getAttributeByUrl(array $attributes): ?ReflectionAttribute
    {
        foreach ($attributes as $attribute) {
            if (URLService::isNeededURL($this->url, $attribute->newInstance()->getParams()['url'])) {
                return $attribute;
            }
        }

        return null;
    }

    private function classResolve(string $class): mixed
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
}