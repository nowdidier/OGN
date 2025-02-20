<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\DiInterface;

class DI extends BaseSingleton
{
    private static DiInterface|null $replace = null;

    public static function object(string $class, array $params = []): object
    {
        if (self::$replace) {
            return self::$replace->object($class, $params);
        }

        return BaseContainer::instance()->get(DiInterface::class)->object($class, $params);
    }

    public static function method(object $obj, string $method, array $params = []): mixed
    {
        if (self::$replace) {
            return self::$replace->method($obj, $method, $params);
        }

        return BaseContainer::instance()->get(DiInterface::class)->method($obj, $method, $params);
    }

    #[ForTestOnly]
    public static function replaceWithMock(DiInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
