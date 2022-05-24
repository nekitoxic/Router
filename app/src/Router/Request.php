<?php

namespace App\Router;
use Attribute;

#[Attribute()]
class Request
{
    private string $url = '';

    private string $name;

    public function __construct(string $url, string $name)
    {
        $this->url  = $url;
        $this->name = $name;
    }

    public function getParams(): array
    {
        return ['url' => $this->url, 'name' => $this->name];
    }
}