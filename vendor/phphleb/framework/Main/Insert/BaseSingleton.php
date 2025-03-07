<?php


namespace Hleb\Main\Insert;

use Hleb\Constructor\Attributes\AvailableAsParent;
use RuntimeException;

#[AvailableAsParent]
class BaseSingleton
{
    private static array $instances = [];

    final protected function __construct() {}

    final protected function __clone() {}

    final protected static function getInstance(): static
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static;
        }
        return self::$instances[$className];
    }

    final public function __wakeup(): void
    {
        throw new RuntimeException("Cannot serialize singleton");
    }

    final public function __serialize(): array
    {
        self::__wakeup();
    }

    final public function __unserialize(array $data)
    {
        self::__wakeup();
    }
}
