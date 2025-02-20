<?php

declare(strict_types=1);

namespace Hleb\Main\Routes;

use Hleb\Base\RollbackInterface;
use Hleb\Main\Insert\BaseAsyncSingleton;

final class BaseRoute extends BaseAsyncSingleton  implements RollbackInterface
{

    private static array $data = [];

    #[\Override]
    public static function rollback(): void
    {
        self::$data = [];
    }

    public static function completion(): array
    {
        $data = self::$data;
        self::$data = [];

        return $data;
    }

    public static function add(array $method): void
    {
        self::$data[] = $method;
    }
}
