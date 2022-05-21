<?php

namespace App\Router\Helpers;

class RouterStringHelper
{
    public static function pathToNamespace(array $arr): array
    {
        $res = [];
        foreach ($arr as $item) {
            $res[] = self::rootFoldersRemove(self::pathReplace($item));
        }

        return $res;
    }

    private static function pathReplace(string $path): string
    {
        return str_replace(['.php', '/src', '/'], ['', '\App', '\\'], $path);
    }

    private static function rootFoldersRemove(string $str): string
    {
        return substr($str, strpos($str, '\App') + 1);
    }
}