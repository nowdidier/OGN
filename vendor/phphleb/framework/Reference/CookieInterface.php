<?php

namespace Hleb\Reference;

use Hleb\HttpMethods\Specifier\DataType;

interface CookieInterface
{

    public function all(): array;

    public function set(string $name, string $value = '', array $options = []): void;

    public function get(string $name): DataType;

    public function setSessionName(string $name): void;

    public function getSessionName(): string;

    public function setSessionId(string $id): void;

    public function getSessionId(): string;

    public function delete(string $name): void;

    public function clear(): void;

    public function has(string $name): bool;

    public function exists(string $name): bool;
}
