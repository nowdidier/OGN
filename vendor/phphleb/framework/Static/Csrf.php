<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\CsrfInterface;

#[Accessible]
final class Csrf extends BaseSingleton
{
    private static CsrfInterface|null $replace = null;

    public static function token(): string
    {
        if (self::$replace) {
            return self::$replace->token();
        }

        return BaseContainer::instance()->get(CsrfInterface::class)->token();
    }

    public static function field(): string
    {
        if (self::$replace) {
            return self::$replace->field();
        }

        return BaseContainer::instance()->get(CsrfInterface::class)->field();
    }

    public static function validate(?string $key): bool
    {
        if (self::$replace) {
            return self::$replace->validate($key);
        }

        return BaseContainer::instance()->get(CsrfInterface::class)->validate($key);
    }

    public static function discover(): string|null
    {
        if (self::$replace) {
            return self::$replace->discover();
        }

        return BaseContainer::instance()->get(CsrfInterface::class)->discover();
    }

    #[ForTestOnly]
    public static function replaceWithMock(CsrfInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
