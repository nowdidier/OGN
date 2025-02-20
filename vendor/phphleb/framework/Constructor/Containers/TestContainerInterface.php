<?php

namespace Hleb\Constructor\Containers;

interface TestContainerInterface
{

    public function get(string $id);

    public function has(string $id): bool;

    public static function getContainer(): \App\Bootstrap\ContainerInterface;

    public static function rollback(): void;
}
