<?php


namespace Hleb\Main\Insert;

use Hleb\Constructor\Attributes\AvailableAsParent;
use RuntimeException;

#[AvailableAsParent]
abstract class BaseAsyncSingleton
{
    final protected function __construct() {}

    final protected function __clone() {}

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

    abstract public static function rollback(): void;

}
