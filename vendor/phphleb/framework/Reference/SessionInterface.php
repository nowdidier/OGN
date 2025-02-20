<?php

namespace Hleb\Reference;

interface SessionInterface
{

    public function all(): array;

    public function get(string|int $name, mixed $default = null): mixed;

    public function getSessionId(): string|null;

    public function set(string|int $name, string|float|int|array|bool|null $data): void;

    public function delete(string|int $name): void;

    public function clear(): void;

    public function has(string|int $name): bool;

    public function exists(string|int $name): bool;

    public function setFlash(string $name, string|float|int|array|bool|null $data, int $repeat = 1): void;

    public function getFlash(string $name, string|float|int|array|bool|null $default = null): string|float|int|array|bool|null;

    public function hasFlash(string $name, string $type = 'old'): bool;

    public function clearFlash(): void;

    public function allFlash(): array;

    public function increment(string $name, int $amount = 1): void;

    public function decrement(string $name, int $amount = 1): void;

    public function counter(string $name, int $amount): void;
}
