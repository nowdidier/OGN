<?php


namespace Hleb\Static;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\Constructor\Cache\OnceResult;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Reference\OnceInterface;

final class Once extends BaseAsyncSingleton implements RollbackInterface
{
    private static OnceInterface|null $replace = null;

    public static function get(callable $func): mixed
    {
        if (self::$replace) {
            return self::$replace->get($func);
        }
        return OnceResult::get($func);
    }

    #[\Override]
    public static function rollback(): void
    {
        if (self::$replace) {
            self::$replace->rollback();
        } else {
            OnceResult::rollback();
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(OnceInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
