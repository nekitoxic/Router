<?php

namespace App\Router\Service;

class URLService
{
    private const SEPARATOR = '/';

    private const URL_ATTRS_REGEXP = "/{([a-zA-Z0-9_]*)}/";

    public static function isNeededURL(string $requestUrl, string $attributeUrl): bool
    {
        if (!self::isEqualLngUrls($requestUrl, $attributeUrl) || !self::isEqualUrls($requestUrl, $attributeUrl)) {
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
        $result = [];

        foreach (self::getUrlsToArray($attributeUrl) as $key => $row) {
            if ($row === $requestUrl[$key]) {
                $result[] = $row;

                continue;
            }

            if ((bool) preg_match(self::URL_ATTRS_REGEXP, $row)) {
                $result[] = $requestUrl[$key];
            }
        }

        return empty(array_diff($requestUrl, $result));
    }

    public static function getKeyValuesFromUrl(string $requestUrl, string $attributeUrl): array
    {
        $attrArr    = self::getUrlsToArray($attributeUrl);
        $result     = [];

        if (!self::isNeededURL($requestUrl, $attributeUrl)) {
            return $result;
        };

        foreach (self::getUrlsToArray($requestUrl) as $key => $row) {
            if ($row === $attrArr[$key]) {
                continue;
            }

            $result[] = $row; 
        }

        return array_combine(self::getNeededArguments($attributeUrl)[1], $result);
    }
}