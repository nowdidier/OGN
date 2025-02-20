<?php


namespace Hleb\HttpMethods\Intelligence\Cookies;

final class ParseCookies
{

    public static function getFromRequestHeaders(
        #[\SensitiveParameter] array $requestHeaders,
    ): array
    {
        $cookies = $requestHeaders['Cookie'] ?? $requestHeaders['cookie'] ?? [];
        if (!\is_array($cookies)) {
            $cookies = \array_map('trim', \explode(';', (string)$cookies));
        }
        return $cookies;
    }
}
