<?php

namespace Hleb\Reference;

interface CacheInterface
{

    public function set(string $key, mixed $value, int $ttl): bool;

    public function setString(string $key, string $value, int $ttl): bool;

    public function setList(string $key, array $value, int $ttl): bool;

    public function setObject(string $key, object $value, int $ttl): bool;

    public function getConform(string $key, callable $func, int $ttl): mixed;

    public function get(string $key, mixed $default): mixed;

    public function getDel(string $key, mixed $default): mixed;

    public function getString(string $key, string|false $default): string|false;

    public function getStringDel(string $key, string|false $default): string|false;

    public function getList(string $key, array|false $default): array|false;

    public function getListDel(string $key, array|false $default): string|false;

    public function getObject(string $key, object|false $default): object|false;

    public function getObjectDel(string $key, object|false $default): object|false;

    public function getMultiple(array $keys, mixed $default = null): array;

    public function setMultiple(array $values, int $ttl): bool;

    public function deleteMultiple(array $values): bool;

    public function delete(string $key): bool;

    public function has(string $key): bool;

    public function isExists(string $key): bool;

    public function getExpire(string $key): int|false;

    public function setExpire(string $key, int $ttl): bool;

    public function count(): int;

    public function clear(): bool;

    public function clearExpired(): void;
}
