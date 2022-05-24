<?php

namespace App\Router;

final class Kernel
{
    private static ?Kernel $instance = null;

    public static function getInstance(): Kernel
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function run()
    {
        (new Invoker($_SERVER['REQUEST_URI']))->castSpell();
    }
}