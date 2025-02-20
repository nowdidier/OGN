<?php

namespace Hleb\Reference;

interface DtoInterface
{

    public function get($name);

    public function set($name, $value): void;

    public function clear(): void;

    public function list(): array;
}
