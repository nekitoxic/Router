<?php

use App\Router\Kernel;

require_once dirname(__DIR__).'/vendor/route/autoloader.php';

// $start = microtime(true);

(new Kernel())::getInstance()::run();

// echo 'Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.';