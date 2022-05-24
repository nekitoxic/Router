<?php

namespace App\Router;

class URLFormatter
{
    private const SEPARATOR = '/';

    private const URL_ATTRS_REGEXP = "/{([a-zA-Z0-9_]*)}/";

    public static function isNeededURL(string $requestUrl, string $attributeUrl): bool
    {
        if (!self::isEqualLngUrls($requestUrl, $attributeUrl)) {
            return false;
        }

        if (!self::isEqualUrls($requestUrl,$attributeUrl)) {
            return false;
        }

        return true;
    }

    public static function getNeededArguments(string $attributeUrl): array
    {
        preg_match_all(self::URL_ATTRS_REGEXP, $attributeUrl, $matches);

        return $matches;
    }

    private static function getUrlsToArray(string $url): array
    {
        return array_filter(explode(self::SEPARATOR, $url));
    }

    private static function isEqualLngUrls(string $requestUrl, string $attributeUrl): bool
    {
        return count(self::getUrlsToArray($attributeUrl)) === count(self::getUrlsToArray($requestUrl));
    }

    private static function isEqualUrls(string $requestUrl, string $attributeUrl): bool
    {
        $requestUrl = self::getUrlsToArray($requestUrl);
        $attributeUrl = self::getUrlsToArray($attributeUrl);
        $result = [];

        foreach ($attributeUrl as $key => $row) {
            if ($row === $requestUrl[$key]) {
                $result[] = $row;

                continue;
            }

            if (preg_match(self::URL_ATTRS_REGEXP, $row)) {
                $result[] = $requestUrl[$key];
            }
        }

        return empty(array_diff($result, $requestUrl));
    }
}