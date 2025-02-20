<?php


namespace Hleb\Main\Insert;

use Hleb\Constructor\Attributes\AvailableAsParent;
use RuntimeException;

#[AvailableAsParent]
class ExternalSingleton
{
    private static array $instances = [];

    final protected function __construct() {}

    final protected function __clone() {}

    final public static function instance(): static
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
