<?php

namespace App\Router;

class Invoker
{
    private string $requestUrl = '';

    public function __construct(string $requestUrl = '')
    {
        $this->requestUrl = $requestUrl;
    }

    public function castSpell(): void
    {
        (new Router($this->requestUrl))->runMethod();
    }
}