<?php

namespace Hleb\Reference;

interface ArrInterface
{

    public function isAssoc(array $array): bool;

    public function append(array $original, array $complement): array;

    public function sortDescByField(array $list, string $field): array;

    public function sortAscByField(array $array, string $field): array;

    public function moveToFirst(array $array, string $key, bool $strict = true): array;

    public function only(array $array, array $keys): array;

    public function divide(array $array): array;

    public function get(array $array, int|string|null $key, mixed $default = null): mixed;

    public function forget(array &$array, array|string|int|float $keys): void;

    public function has(array $array, string|array $keys): bool;

    public function add(array $array, string|int|float $key, mixed $value): array;

    public function set(array &$array, string|int|null $key, mixed $value): array;

    public function expand(iterable $array): array;
}
