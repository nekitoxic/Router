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
            $class  = DIService::classResolve($controller['class']);
            $method = new ReflectionMethod($class, $controller['method']);

            $method->invokeArgs($class, DIService::methodResolve($method, $this->requestUrl));
        }

        return $responce->toArray();
    }

    private function getControllerByUrl(): array
    {
        foreach (StringHelper::pathToNamespace(FileHelper::getControllersFile()) as $class) {
            foreach ((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $attribute = URLService::getNeededAttributeByUrl($this->requestUrl, $method->getAttributes(Endpoint::class));

                if (null !== $attribute) {
                    return ['class' => $class, 'method' => $method->name];
                }
            }
        }

        return [];
    }
}