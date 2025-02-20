<?php

namespace Hleb\Reference;

interface DiInterface
{

    public function object(string $class, array $params = []): object;

    public function method(object $obj, string $method, array $params = []): mixed;
}
