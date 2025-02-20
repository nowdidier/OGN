<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\SessionInterface;

#[Accessible]
class Session extends BaseSingleton
{
    private static SessionInterface|null $replace = null;

    public static function all(): array
    {
        if (self::$replace) {
            return self::$replace->all();
        }

        return BaseContainer::instance()->get(SessionInterface::class)->all();
    }

    public static function get(string|int $name, mixed $default = null): mixed
    {
        if (self::$replace) {
            return self::$replace->get($name, $default);
        }

        return BaseContainer::instance()->get(SessionInterface::class)->get($name, $default);
    }

    public static function getSessionId(): string|null
    {
        if (self::$replace) {
            return self::$replace->getSessionId();
        }

        return BaseContainer::instance()->get(SessionInterface::class)->getSessionId();
    }

    public static function set(string|int $name, string|float|int|array|bool|null $data): void
    {
        if (self::$replace) {
            self::$replace->set($name, $data);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->set($name, $data);
        }
    }

    public static function delete(string|int $name): void
    {
        if (self::$replace) {
            self::$replace->delete($name);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->delete($name);
        }
    }

    public static function clear(): void
    {
        if (self::$replace) {
            self::$replace->clear();
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->clear();
        }
    }

    public static function has(string|int $name): bool
    {
        if (self::$replace) {
            return self::$replace->has($name);
        }

        return BaseContainer::instance()->get(SessionInterface::class)->has($name);
    }

    public static function exists(string|int $name): bool
    {
        if (self::$replace) {
            return self::$replace->exists($name);
        }

        return BaseContainer::instance()->get(SessionInterface::class)->exists($name);
    }

    public static function setFlash(string $name, string|float|int|array|bool|null $data, int $repeat = 1): void
    {
        if (self::$replace) {
            self::$replace->setFlash($name, $data, $repeat);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->setFlash($name, $data, $repeat);
        }
    }

    public static function getFlash(string $name, string|float|int|array|bool|null $default = null): string|float|int|array|bool|null
    {
        if (self::$replace) {
            return self::$replace->getFlash($name, $default);
        }

        return BaseContainer::instance()->get(SessionInterface::class)->getFlash($name, $default);
    }

    public static function hasFlash(string $name, string $type = 'old'): bool
    {
        if (self::$replace) {
            return self::$replace->hasFlash($name);
        }

        return BaseContainer::instance()->get(SessionInterface::class)->hasFlash($name);
    }

    public static function clearFlash(): void
    {
        if (self::$replace) {
            self::$replace->clearFlash();
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->clearFlash();
        }
    }

    public static function allFlash(): array
    {
        if (self::$replace) {
            return self::$replace->allFlash();
        }

        return BaseContainer::instance()->get(SessionInterface::class)->allFlash();
    }

    public static function increment(string $name, int $amount = 1): void
    {
        if (self::$replace) {
            self::$replace->increment($name, $amount);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->increment($name, $amount);
        }
    }

    public static function decrement(string $name, int $amount = 1): void
    {
        if (self::$replace) {
            self::$replace->decrement($name, $amount);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->decrement($name, $amount);
        }
    }

    public static function counter(string $name, int $amount): void
    {
        if (self::$replace) {
            self::$replace->counter($name, $amount);
        } else {
            BaseContainer::instance()->get(SessionInterface::class)->counter($name, $amount);
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(SessionInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
