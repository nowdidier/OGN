<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Init\ShootOneselfInTheFoot\ArrForTest;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\ArrInterface;

#[Accessible]
final class Arr extends BaseSingleton
{
    private static ArrInterface|null $replace = null;

    public static function isAssoc(array $array): bool
    {
        if (self::$replace) {
            return self::$replace->isAssoc($array);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->isAssoc($array);
    }

    public static function append(array $original, array $complement): array
    {
        if (self::$replace) {
            return self::$replace->append($original, $complement);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->append($original, $complement);
    }

    public static function sortDescByField(array $list, string $field): array
    {
        if (self::$replace) {
            return self::$replace->sortDescByField($list, $field);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->sortDescByField($list, $field);
    }

    public static function sortAscByField(array $array, string $field): array
    {
        if (self::$replace) {
            return self::$replace->sortAscByField($array, $field);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->sortAscByField($array, $field);
    }

    public static function moveToFirst(array $array, string $key, bool $strict = true): array
    {
        if (self::$replace) {
            return self::$replace->moveToFirst($array, $key, $strict);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->moveToFirst($array, $key, $strict);
    }

    public static function only(array $array, array $keys): array
    {
        if (self::$replace) {
            return self::$replace->only($array, $keys);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->only($array, $keys);
    }

    public static function divide(array $array): array
    {
        if (self::$replace) {
            return self::$replace->divide($array);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->divide($array);
    }

    public static function get(array $array, int|string|null $key, mixed $default = null): mixed
    {
        if (self::$replace) {
            return self::$replace->get($array, $key, $default);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->get($array, $key, $default);
    }

    public static function forget(array &$array, array|string|int|float $keys): void
    {
        if (self::$replace) {
            self::$replace->forget($array, $keys);
        } else {
            BaseContainer::instance()->get(ArrInterface::class)->forget($array, $keys);
        }
    }

    public static function has(array $array, string|array $keys): bool
    {
        if (self::$replace) {
            return self::$replace->has($array, $keys);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->has($array, $keys);
    }

    public static function add(array $array, string|int|float $key, mixed $value): array
    {
        if (self::$replace) {
            return self::$replace->add($array, $key, $value);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->add($array, $key, $value);
    }

    public static function set(array &$array, string|int|null $key, mixed $value): array
    {
        if (self::$replace) {
            return self::$replace->set($array, $key, $value);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->set($array, $key, $value);
    }

    public static function expand(iterable $array): array
    {
        if (self::$replace) {
            return self::$replace->expand($array);
        }

        return BaseContainer::instance()->get(ArrInterface::class)->expand($array);
    }

    #[ForTestOnly]
    public static function replaceWithMock(ArrInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
