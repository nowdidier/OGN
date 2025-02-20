<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\RouterInterface;

#[Accessible]
final class Router extends BaseSingleton
{
    private static RouterInterface|null $replace = null;

    public static function name(): ?string
    {
        if (self::$replace) {
            return self::$replace->name();
        }

        return BaseContainer::instance()->get(RouterInterface::class)->name();
    }

    public static function url(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): string
    {
        if (self::$replace) {
            return self::$replace->url($routeName, $replacements, $endPart, $method);
        }

        return BaseContainer::instance()->get(RouterInterface::class)->url($routeName, $replacements, $endPart, $method);
    }

    public static function address(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): false|string
    {
        if (self::$replace) {
            return self::$replace->address($routeName, $replacements, $endPart, $method);
        }

        return BaseContainer::instance()->get(RouterInterface::class)->address($routeName, $replacements, $endPart, $method);
    }

    public static function data(): array
    {
        if (self::$replace) {
            return self::$replace->data();
        }

        return BaseContainer::instance()->get(RouterInterface::class)->data();
    }

    #[ForTestOnly]
    public static function replaceWithMock(RouterInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
