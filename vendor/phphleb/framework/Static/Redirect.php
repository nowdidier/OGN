<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\RedirectInterface;

#[Accessible]
final class Redirect extends BaseSingleton
{
    private static RedirectInterface|null $replace = null;

    public static function to(string $location, int $status = 302): void
    {
        if (self::$replace) {
            self::$replace->to($location, $status);
        } else {
            BaseContainer::instance()->get(RedirectInterface::class)->to($location, $status);
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(RedirectInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
