<?php

namespace App\Router\Service;

class CoreFileService
{
    private const CONTROLLERS_DIRECTORY = '/Controllers';

    public static function getControllersFile(): array
    {
        return self::recursiveFileScan(dirname(dirname(__DIR__)) . self::CONTROLLERS_DIRECTORY);
    }

    private static function arrayFlatten(array $arr): array
    {
        $res = [];

        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $res = array_merge($res, self::arrayFlatten($value));
            } else {
                $res[$key] = $value;
            }
        }

        return $res;
    }

    private static function recursiveFileScan(string $directory): array
    {
        $res = [];

        foreach (array_diff(scandir($directory), ['.', '..']) as $file) {
            $fullPath = $directory. '/' . $file;

            if (is_dir($fullPath)) {
                $res[] = self::recursiveFileScan($directory. '/' . $file);
            }

            if (is_file($fullPath)) {
                $res[] = $fullPath;
            }
        }

        return self::arrayFlatten($res);
    }
}