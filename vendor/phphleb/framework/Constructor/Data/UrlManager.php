<?php


namespace Hleb\Constructor\Data;

use Hleb\Helpers\ArrayHelper;
use Hleb\Helpers\RangeChecker;
use Hleb\InvalidArgumentException;
use Hleb\Main\Routes\Prepare\Optimizer;

final class UrlManager
{

    public function getUrlAddressByName(array $routes, string $name, array $replacements = [], ?bool $endPart = null): string
    {
        foreach ($routes as $route) {
            if (!isset($route['i']) || $route['i'] !== $name) {
                continue;
            }
            $address = \trim($route['a'], '/');
            if (!$address) {
                return '/';
            }
            if (\str_contains($address, '?') && ($endPart === false ||
                ($endPart === null && \count($replacements) === \substr_count($address, '{') - 1))
            ) {
                $parts = \explode('/', $address);
                if (\count($parts) === 1) {
                    return '/';
                }
                \array_pop($parts);
                $address = \implode('/', $parts);
            }
            if (isset($route['m'])) {
                $address = $this->getFromVariableRoute($address, $replacements);
            } else if (isset($route['d'])) {
                if (ArrayHelper::isAssoc($replacements)) {
                    $address = $this->getFromDynamicRouteAssoc($address, $replacements, $endPart, $route['w'] ?? null);
                } else {
                    $address = $this->getFromDynamicRoute($address, $replacements, $endPart, $route['w'] ?? null);
                }
            } else {
                $address = $this->getFromStandardRoute($address, $replacements);
            }
            if (!$address) {
                return '/';
            }

            DynamicParams::isEndingUrl() and $address .= '/';

            if (\str_contains($address, '{')) {
                \preg_match_all('/\{(.*?)\}/', $address, $matches);

                throw new InvalidArgumentException('Wrong number of replacement parts for URL: ' . \implode(',', $matches[1] ?? []) . " Route name `{$name}`");
            }

            return '/' . $address;
        }

        throw new InvalidArgumentException("No match for route by name `{$name}`");
    }

    private function getFromStandardRoute(string $address, array $replacements): string
    {
        if ($replacements) {
            throw new InvalidArgumentException('It is not possible to make a replacement if there are no substitution options.');
        }
        return \str_replace('?', '', $address);
    }

    private function getFromVariableRoute(string $address, array $replacements): string
    {
        $parts = \explode('/', $address);
        $end = \ltrim(\array_pop($parts), '.');
        $address = \implode('/', $parts);


        if (ArrayHelper::isAssoc($replacements)) {
            throw new InvalidArgumentException('The replacement array must not be associative.');
        }


        if (!(new RangeChecker($end))->check(\count($replacements))) {
            throw new InvalidArgumentException('Wrong number of replacement parts for URL.');
        }
        if (\count($replacements)) {
            $address .= '/' . \implode('/', $replacements);
        }

        return $address;
    }

    private function getFromDynamicRouteAssoc(string $address, array $replacements, ?bool $endPart, ?array $condition): string
    {
        if (!$this->checkPartCount($address, $replacements, $endPart)) {
            $error = 'Wrong number of replacement parts for URL.';
            if (\str_contains($address, '?')) {
                $found = false;
                foreach ($replacements as $key => $value) {
                    if (\str_contains($address, "{{$key}?}")) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $error .= ' It is possible that the last part `endPart: false` is missing.';
                }
            }
            throw new InvalidArgumentException($error);
        }
        $address = \str_replace('?', '', $address);

        foreach ($replacements as $key => $param) {
            if ($condition && isset($condition[$key]) && !$this->checkWhereCondition($key, $condition, $param)) {
                throw new InvalidArgumentException('Parts of the URL did not pass the where() condition in the route.');
            }
            $address = \str_replace('{' . $key . '}', $param, $address);
        }

        return $address;
    }

    private function getFromDynamicRoute(string $address, array $replacements, ?bool $endPart, ?array $condition): string
    {
        $parts = \explode('/', $address);
        $keys = [];


        if (!$this->checkPartCount($address, $replacements, $endPart)) {
            $error = 'Wrong number of replacement parts for URL.';
            if (\str_contains($address, '?')) {
                $error .= ' It is possible that the last part `endPart: false` is missing.';
            }
            throw new InvalidArgumentException($error);
        }
        $isUnstable = \str_contains($address, '?');
        if ($isUnstable) {
            $address = \str_replace('?', '', $address);
        }

        foreach($parts as $part) {
            if (\str_contains($part, '{')) {
                $keys[] = \trim($part, '@{}?');
            }
        }

        foreach ($replacements as $param) {
            $key = \array_shift($keys);


            if ($condition && isset($condition[$key]) && !$this->checkWhereCondition($key, $condition, $param)) {
                throw new InvalidArgumentException('Parts of the URL did not pass the where() condition in the route.');
            }
            $address = \str_replace('{' . $key . '}', $param, $address);
        }

        return $address;
    }

    private function checkWhereCondition(string $key, array $condition, string $param): bool
    {
        $reg = $condition[$key];
        if (!\str_starts_with($reg, '/')) {
            $reg = "/^$reg$/";
        }
        return (bool)\preg_match($reg, $param);
    }

    private function checkPartCount(string $address, array $replacements, ?bool $endPart): bool
    {
        $countTags = \substr_count($address, '{');
        $originCount = \count($replacements);

        if ($originCount !== $countTags && !\str_contains($address, '?')) {
            return false;
        }

        if ($endPart === null) {
            if ($originCount < $countTags - 1 || $originCount > $countTags) {
                return false;
            }
        } else if ($originCount < $countTags - (int)!$endPart) {
            return false;
        }

        return true;
    }
}
