<?php

namespace App\Router;

use App\Router\Helpers\RouterFileHelper as FileHelper;
use App\Router\Helpers\RouterStringHelper as StringHelper;
use ReflectionClass;
use ReflectionMethod;

class Invoker
{
    private string $requestUrl = '';

    public function __construct(string $requestUrl = '')
    {
        $this->requestUrl = $requestUrl;
    }

    public function castSpell(): array
    {
        $responce   = new Responce(404, $this->requestUrl);
        $controller = $this->getControllerByUrl();

        if (!empty($controller)) {
            $class  = ReflectionResolver::classResolve($controller['class']);
            $method = new ReflectionMethod($class, $controller['method']);

            // $method->invokeArgs($class, ReflectionResolver::methodResolve($method));
        }

        return $responce->toArray();
    }

    private function getControllerByUrl(): array
    {
        foreach (StringHelper::pathToNamespace(FileHelper::getControllersFile()) as $className) {
            foreach ((new ReflectionClass($className))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                foreach ($method->getAttributes() as $attribute) {
                    if (URLService::isNeededURL($this->requestUrl, $attribute->newInstance()->getParams()['url'])) {
                        return ['class' => $className, 'method' => $method->name];
                    }
                }
            }
        }

        return [];
    }
}