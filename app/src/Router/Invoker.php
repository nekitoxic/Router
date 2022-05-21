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
            $class = new $controller['class']();

            var_dump((new ReflectionMethod($class, $controller['method']))->invoke($class));
        }

        return $responce->toArray();
    }

    private function getControllerByUrl(): array
    {
        foreach (StringHelper::pathToNamespace(FileHelper::getControllersFile()) as $controller) {
            foreach ((new ReflectionClass($controller))->getMethods() as $method) {
                foreach ($method->getAttributes(Request::class) as $attribute) {
                    if($attribute->newInstance()->getParams()['url'] === $this->requestUrl) {
                        return ['class' => $controller, 'method' => $method->name];
                    }
                }
            }
        }

        return [];
    }
}