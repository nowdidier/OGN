<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\PathInterface;

#[Accessible]
final class Path extends BaseSingleton
{
    private static PathInterface|null $replace = null;

    public static function relative(string $path): string
    {
        if (self::$replace) {
            return self::$replace->relative($path);
        }

        return BaseContainer::instance()->get(PathInterface::class)->relative($path);
    }

    public static function createDirectory(string $path, int $permissions = 0775): bool
    {
        if (self::$replace) {
            return self::$replace->createDirectory($path, $permissions);
        }

        return BaseContainer::instance()->get(PathInterface::class)->createDirectory($path, $permissions);
    }

    public static function exists(string $path): bool
    {
        if (self::$replace) {
            return self::$replace->exists($path);
        }

        return BaseContainer::instance()->get(PathInterface::class)->exists($path);
    }

    public static function contents(string $path, bool $use_include_path = false, $context = null, int $offset = 0, ?int $length = null): false|string
    {
        if (self::$replace) {
            return self::$replace->contents($path, $use_include_path, $context, $offset, $length);
        }

        return BaseContainer::instance()->get(PathInterface::class)->contents($path, $use_include_path, $context, $offset, $length);
    }

    public static function put(string $path, mixed $data, int $flags = 0, $context = null): false|int
    {
        if (self::$replace) {
            return self::$replace->put($path, $data, $flags, $context);
        }

        return BaseContainer::instance()->get(PathInterface::class)->put($path, $data, $flags, $context);
    }

    public static function isDir(string $path): bool
    {
        if (self::$replace) {
            return self::$replace->isDir($path);
        }

        return BaseContainer::instance()->get(PathInterface::class)->isDir($path);
    }

    public static function getReal(string $keyOrPath): false|string
    {
        if (self::$replace) {
            return self::$replace->getReal($keyOrPath);
        }

        return BaseContainer::instance()->get(PathInterface::class)->getReal($keyOrPath);
    }

    public static function get(string $keyOrPath): false|string
    {
        if (self::$replace) {
            return self::$replace->get($keyOrPath);
        }

        return BaseContainer::instance()->get(PathInterface::class)->get($keyOrPath);
    }

    #[ForTestOnly]
    public static function replaceWithMock(PathInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
