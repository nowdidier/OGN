<?php


namespace Hleb\Main;

final class RouteExtractor
{

    private const PATTERN = '[verb]';

    public function getCalledClassAndMethod(string $controllerName, string $methodName, int $countTags, array $params): array
    {
        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }
            $tag = '<' . $key . '>';
            $reformatValue = $this->reformatValue((string)$value);
            if ($reformatValue === false) {
                return [$controllerName, $methodName];
            }
            if (\str_starts_with($methodName, $tag)) {
                $methodName = \str_replace($tag, \lcfirst($reformatValue), $methodName);
                if ($countTags === 1) {
                    return [$controllerName, $methodName];
                }
            }
            if (\str_contains($methodName, $tag)) {
                $methodName = \str_replace($tag, \lcfirst($reformatValue), $methodName);
                if ($countTags === 1) {
                    return [$controllerName, $methodName];
                }
            }
            if (\str_contains($controllerName, $tag)) {
                $controllerName = \str_replace($tag, $reformatValue, $controllerName);
            }
        }

        return [$controllerName, $methodName];
    }

    public function replacePattern(string $controller, string $method, string $insertMethod): array
    {
        $insert = \ucfirst(\strtolower($insertMethod));

        if (\str_contains($controller, self::PATTERN)) {
            $controller = \str_replace(self::PATTERN, $insert, $controller);
        }
        if (\str_contains($method, self::PATTERN)) {
            if (\str_starts_with($method, self::PATTERN)) {
                $insert = \lcfirst($insert);
            }
            $method = \str_replace(self::PATTERN, $insert, $method);
        }

        return [$controller, $method];
    }

    private function reformatValue(string $value): false|string
    {
        $parts = \explode('-', $value);
        $result = '';
        foreach ($parts as $part) {
            if ($part === '') {
                return false;
            }
            $result .= \ucfirst($part);
        }
        return $result;
    }

}
