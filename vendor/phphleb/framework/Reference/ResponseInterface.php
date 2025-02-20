<?php

namespace Hleb\Reference;

use Hleb\HttpMethods\External\Response as SystemResponse;

interface ResponseInterface
{

    public function getInstance(): ?SystemResponse;

    public function getStatus(): int;

    public function setStatus(int $status, ?string $reason = null): void;

    public function getHeaders(): array;

    public function replaceHeaders(array $headers): void;

    public function setHeader(string $name, int|float|string $value, bool $replace = true): void;

    public function hasHeader(string $name): bool;

    public function getHeader(string $name): array;

    public function addHeaders(array $headers, bool $replace = true): void;

    public function get(): string;

    public function set(string|\Stringable $body, ?int $status = null): void;

    public function add(mixed $content): void;

    public function getBody(): string;

    public function setBody($body): void;

    public function addToBody(mixed $content): void;

    public function clearBody(): void;

    public function removeFromBody(): mixed;

    public function getVersion(): string;

    public function setVersion(string $version): void;

    public function getReason(): ?string;

    public static function init(SystemResponse $response): void;

    public static function rollback(): void;
}
