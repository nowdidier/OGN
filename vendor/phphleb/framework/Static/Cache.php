<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Init\ShootOneselfInTheFoot\CacheForTest;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\CacheInterface;

#[Accessible]
final class Cache extends BaseSingleton
{
    final public const DEFAULT_TIME = 60;

    private static CacheInterface|null $replace = null;

    public static function set(string $key, mixed $value, int $ttl = self::DEFAULT_TIME): bool
    {
        if (self::$replace) {
            return self::$replace->set($key, $value, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->set($key, $value, $ttl);
    }

    public static function setString(string $key, string $value, int $ttl = self::DEFAULT_TIME): bool
    {
        if (self::$replace) {
            return self::$replace->setString($key, $value, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->setString($key, $value, $ttl);
    }

    public static function setList(string $key, array $value, int $ttl = self::DEFAULT_TIME): bool
    {
        if (self::$replace) {
            return self::$replace->setList($key, $value, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->setList($key, $value, $ttl);
    }

    public static function setObject(string $key, object $value, int $ttl = self::DEFAULT_TIME): bool
    {
        if (self::$replace) {
            return self::$replace->setObject($key, $value, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->setObject($key, $value, $ttl);
    }

    public static function getConform(string $key, callable $func, int $ttl = self::DEFAULT_TIME): mixed
    {
        if (self::$replace) {
            return self::$replace->getConform($key, $func, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getConform($key, $func, $ttl);
    }

    public static function get(string $key, mixed $default = false): mixed
    {
        if (self::$replace) {
            return self::$replace->get($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->get($key, $default);
    }

    public static function getDel(string $key, mixed $default = false): mixed
    {
        if (self::$replace) {
            return self::$replace->getDel($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getDel($key, $default);
    }

    public static function getString(string $key, string|false $default = false): string|false
    {
        if (self::$replace) {
            return self::$replace->getString($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getString($key, $default);
    }

    public static function getStringDel(string $key, string|false $default = false): string|false
    {
        if (self::$replace) {
            return self::$replace->getStringDel($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getStringDel($key, $default);
    }

    public static function getList(string $key, array|false $default = false): array|false
    {
        if (self::$replace) {
            return self::$replace->getList($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getList($key, $default);
    }

    public static function getListDel(string $key, array|false $default = false): string|false
    {
        if (self::$replace) {
            return self::$replace->getListDel($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getListDel($key, $default);
    }

    public static function getObject(string $key, object|false $default = false): object|false
    {
        if (self::$replace) {
            return self::$replace->getObject($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getObject($key, $default);
    }

    public static function getObjectDel(string $key, object|false $default = false): object|false
    {
        if (self::$replace) {
            return self::$replace->getObjectDel($key, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getObjectDel($key, $default);
    }

    public static function getMultiple(array $keys, mixed $default = null): array
    {
        if (self::$replace) {
            return self::$replace->getMultiple($keys, $default);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getMultiple($keys, $default);
    }

    public static function setMultiple(array $values, int $ttl = self::DEFAULT_TIME): bool
    {
        if (self::$replace) {
            return self::$replace->setMultiple($values, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->setMultiple($values, $ttl);
    }

    public static function deleteMultiple(array $values): bool
    {
        if (self::$replace) {
            return self::$replace->deleteMultiple($values);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->deleteMultiple($values);
    }

    public static function delete(string $key): bool
    {
        if (self::$replace) {
            return self::$replace->delete($key);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->delete($key);
    }

    public static function has(string $key): bool
    {
        if (self::$replace) {
            return self::$replace->has($key);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->has($key);
    }

    public static function isExists(string $key): bool
    {
        if (self::$replace) {
            return self::$replace->isExists($key);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->isExists($key);
    }

    public static function getExpire(string $key): int|false
    {
        if (self::$replace) {
            return self::$replace->getExpire($key);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->getExpire($key);
    }

    public static function setExpire(string $key, int $ttl): bool
    {
        if (self::$replace) {
            return self::$replace->setExpire($key, $ttl);
        }

        return BaseContainer::instance()->get(CacheInterface::class)->setExpire($key, $ttl);
    }

    public static function count(): int
    {
        if (self::$replace) {
            return self::$replace->count();
        }

        return BaseContainer::instance()->get(CacheInterface::class)->count();
    }

    public static function clear(): bool
    {
        if (self::$replace) {
            return self::$replace->clear();
        }

        return BaseContainer::instance()->get(CacheInterface::class)->clear();
    }

    public static function clearExpired(): void
    {
        if (self::$replace) {
            self::$replace->clearExpired();
        } else {
            BaseContainer::instance()->get(CacheInterface::class)->clearExpired();
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(CacheInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
