<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Init\ShootOneselfInTheFoot\CookiesForTest;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\CookieInterface;

#[Accessible]
final class Cookies extends BaseSingleton
{
    final public const OPTION_KEYS = ['expires', 'path', 'domain', 'secure', 'httponly', 'samesite'];

    final public const SAMESITE_VALUES = ['Strict', 'None', 'Lax'];

    private static CookieInterface|null $replace = null;

    public static function set(string $name, string $value = '', array $options = []): void
    {
        if (self::$replace) {
            self::$replace->set($name, $value, $options);
        } else {
            BaseContainer::instance()->get(CookieInterface::class)->set($name, $value, $options);
        }
    }

    public static function get(string $name): DataType
    {
        if (self::$replace) {
            return self::$replace->get($name);
        }

        return BaseContainer::instance()->get(CookieInterface::class)->get($name);
    }

    public static function all(): array
    {
        if (self::$replace) {
            return self::$replace->all();
        }

        return BaseContainer::instance()->get(CookieInterface::class)->all();
    }

    public static function setSessionName(string $name): void
    {
        if (self::$replace) {
            self::$replace->setSessionName($name);
        } else {
            BaseContainer::instance()->get(CookieInterface::class)->setSessionName($name);
        }
    }

    public static function getSessionName(): string
    {
        if (self::$replace) {
            return self::$replace->getSessionName();
        }

        return BaseContainer::instance()->get(CookieInterface::class)->getSessionName();
    }

    public static function setSessionId(string $id): void
    {
        if (self::$replace) {
            self::$replace->setSessionId($id);
        } else {
            BaseContainer::instance()->get(CookieInterface::class)->setSessionId($id);
        }
    }

    public static function getSessionId(): string
    {
        if (self::$replace) {
            return self::$replace->getSessionId();
        }

        return BaseContainer::instance()->get(CookieInterface::class)->getSessionId();
    }

    public static function delete(string $name): void
    {
        if (self::$replace) {
            self::$replace->delete($name);
        } else {
            BaseContainer::instance()->get(CookieInterface::class)->delete($name);
        }
    }

    public static function clear(): void
    {
        if (self::$replace) {
            self::$replace->clear();
        } else {
            BaseContainer::instance()->get(CookieInterface::class)->clear();
        }
    }

    public function has(string $name): bool
    {
        if (self::$replace) {
            return self::$replace->has($name);
        }

        return BaseContainer::instance()->get(CookieInterface::class)->has($name);
    }

    public function exists(string $name): bool
    {
        if (self::$replace) {
            return self::$replace->exists($name);
        }

        return BaseContainer::instance()->get(CookieInterface::class)->exists($name);
    }

    #[ForTestOnly]
    public static function replaceWithMock(CookieInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
