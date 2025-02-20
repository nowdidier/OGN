<?php

namespace Hleb\Reference;

use Hleb\HttpMethods\External\RequestUri;
use Hleb\HttpMethods\Specifier\DataType;

interface RequestInterface
{

    public function getMethod(): string;

    public function isMethod(string $name): bool;

    public function get(string|int $name): ?DataType;

    public function allGet(bool $cleared = true): array;

    public function post(string|int $name): DataType;

    public function allPost(bool $cleared = true): array;

    public function param(string $name): DataType;

    public function data(): array;

    public function input(): array;

    public function rawData(): array;

    public function getParsedBody(): array;

    public function getUri(): RequestUri;

    public function getRawBody(): string;

    public function isAjax(): bool;

    public function getFiles(string|int|null $name = null): null|array|object;

    public function isHttpSecure(): bool;

    public function getHost(): string;

    public function getHostName(): string;

    public function getAddress(): string;

    public function getProtocolVersion(): string;

    public function getHttpScheme(): string;

    public function getSchemeAndHost(): string;

    public function getHeaders(): array;

    public function hasHeader($name): bool;

    public function getHeader($name): array;

    public function getSingleHeader($name): DataType;

    public function getHeaderLine($name): string;

    public function server($name): mixed;

    public function isCurrent(string $uri): bool;

    public function getStreamBody(): ?object;
}
