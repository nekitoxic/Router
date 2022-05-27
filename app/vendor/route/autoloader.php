<?php
spl_autoload_register(function($className) {
    $fileName = dirname(dirname(__DIR__)) . str_replace(['App\\', '\\'], ['/src/', '/'], $className) . '.php';

	if (is_file($fileName)) {
		require $fileName;
	}
});