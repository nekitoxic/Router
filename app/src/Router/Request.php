<?php

namespace App\Router;
use Attribute;

#[Attribute()]
class Request
{
    private static string $url = '';

    private static string $name;

    public function __construct(string $url, string $name)
    {
        self::$url  = $url;
        self::$name = $name;
    }

    public function getParams(): array
    {
        return ['url' => self::$url, 'name' => self::$name];
    }
}