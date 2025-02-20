<?php


namespace Hleb\Constructor\Data;

use Hleb\Base\RollbackInterface;
use Hleb\Main\Insert\BaseAsyncSingleton;

final class DebugAnalytics extends BaseAsyncSingleton implements RollbackInterface
{
    final public const CLASSES_AUTOLOAD = 'classes.autoload';

    final public const INSERT_TEMPLATE = 'insert.template';

    final public const DATA_DEBUG = 'data.debug';

    final public const DB_DEBUG = 'db.debug';

    final public const INITIATOR = 'initiator';

    final public const MIDDLEWARE = 'middleware';

    final public const HL_CHECK = 'hl.check';

    private static array $data = [];

    #[\Override]
    public static function rollback(): void
    {
        self::$data = [];
    }

    public static function getData(): array
    {
        return self::$data;
    }

    public static function setData(#[\SensitiveParameter] array $data): void
    {
        self::$data = $data;
    }

    public static function addData(
        #[\SensitiveParameter] string $name,
        #[\SensitiveParameter] mixed  $value
    ): void
    {
        empty(self::$data[$name]) and self::$data[$name] = [];
        self::$data[$name][] = $value;
    }
}
