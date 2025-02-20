<?php

namespace Hleb\Reference;

interface RouterInterface
{

    public function name(): ?string;

    public function url(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): false|string;

    public function address(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): false|string;

    public function data(): array;
}
