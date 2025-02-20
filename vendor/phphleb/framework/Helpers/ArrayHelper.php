<?php

declare(strict_types=1);

namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;

#[Immutable] #[Accessible]
final class ArrayHelper
{

    #[Pure]
    public static function isAssoc(array $array): bool
    {
        if (!$array) {
            return false;
        }
        return !\array_is_list($array);
    }

    #[Pure]
    public static function append(array $original, array $complement): array
    {
        $result = [];
        foreach ($original as $key => $value) {
            if (\is_int($key)) {
                return $original;
            }
            if (!\array_key_exists($key, $complement)) {
                $result[$key] = $value;
                continue;
            }
            if (\is_array($value)) {
                $result[$key] = self::append($value, $complement[$key]);
                continue;
            }
            $result[$key] = $complement[$key];
        }
        return $result;
    }

    public static function sortDescByField(array $list, string $field): array
    {
        \usort($list, static function ($a, $b) use ($field) {
            if (\is_numeric($a[$field])) {
                $a[$field] = (string)$a[$field];
            }
            if (\is_numeric($b[$field])) {
                $b[$field] = (string)$b[$field];
            }
            return \strnatcmp($b[$field], $a[$field]);
        });
        return $list;
    }

    public static function sortAscByField(array $array, string $field): array
    {
        \usort($array, static function ($a, $b) use ($field) {
            if (\is_numeric($a[$field])) {
                $a[$field] = (string)$a[$field];
            }
            if (\is_numeric($b[$field])) {
                $b[$field] = (string)$b[$field];
            }
            return \strnatcmp($a[$field], $b[$field]);
        });
        return $array;
    }

    public static function moveToFirst(array $array, string $key, bool $strict = true): array
    {
        if (!$array) {
            return [];
        }
        if (!\array_key_exists($key, $array)) {
            $strict and throw new \RuntimeException('`' . $key . '` key not found in array');
            return $array;
        }
        $isAssoc = self::isAssoc($array);
        $value = $array[$key];
        unset($array[$key]);

        $result = \array_merge([$key => $value], $array);
        if ($isAssoc) {
            return $result;
        }
        return \array_values($result);
    }

    #[Pure]
    public static function only(array $array, array $keys): array
    {
        $result = \array_intersect_key($array, \array_flip($keys));

        return self::isAssoc($array) ? $result : \array_values($result);
    }

    #[Pure]
    public static function divide(array $array): array
    {
        return [\array_keys($array), \array_values($array)];
    }

    public static function get(array $array, int|string|null $key, mixed $default = null): mixed
    {
        if ($key === null || !$array) {
            return $default;
        }
        if (\array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (!\str_contains((string)$key, '.')) {
            return $array[$key] ?? $default;
        }
        foreach (\explode('.', $key) as $part) {
            if (\is_array($array) && \array_key_exists($part, $array)) {
                $array = $array[$part];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function forget(array &$array, array|string|int $keys): void
    {
        $original = &$array;
        $keys = (array)$keys;
        if (\count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            if (\array_key_exists($key, $array)) {
                unset($array[$key]);
                continue;
            }
            $parts = \explode('.', $key);
            $array = &$original;

            while (\count($parts) > 1) {
                $part = \array_shift($parts);
                if (isset($array[$part]) && \is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[\array_shift($parts)]);
        }
    }

    public static function has(array $array, string|array|int $keys): bool
    {
        $keys = (array)$keys;
        if (!$array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;
            if (\array_key_exists( $key, $array)) {
                continue;
            }
            foreach (\explode('.', $key) as $segment) {
                if (\is_array($subKeyArray) && \array_key_exists($segment, $subKeyArray)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    public static function add(array $array, string|int $key, mixed $value): array
    {
        if (self::get($array, $key) === null) {
            self::set($array, $key, $value);
        }

        return $array;
    }

    public static function set(array &$array, string|int|null $key, mixed $value): array
    {
        if ($key === null) {
            return $array = $value;
        }

        $keys = \is_string($key) ? \explode('.', $key) : [$key];

        foreach ($keys as $i => $key) {
            if (\count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            if (!isset($array[$key]) || !\is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        $array[\array_shift($keys)] = $value;

        return $array;
    }

    public static function expand(iterable $array): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            self::set($results, $key, $value);
        }

        return $results;
    }

    public static function moveFirstByValue(array $array, mixed $value, bool $strict = true): array
    {
        $key = \array_search($value, $array, true);
        if ($key === false) {
            $strict and throw new \RuntimeException('Value not found in array');
            return $array;
        }
        $assoc = self::isAssoc($array);
        unset($array[$key]);
        if ($assoc) {
            $new = [$key => $value];
            foreach($array as $name => $value) {
                $new[$name] = $value;
            }
            return $new;
        }
        return \array_values(\array_merge([$key => $value], $array));

    }
}
