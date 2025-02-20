<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use App\Bootstrap\ContainerFactory;
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\Constructor\Containers\CoreContainer;
use Hleb\CoreProcessException;
use Hleb\Init\ShootOneselfInTheFoot\ContainerForTest;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Constructor\Containers\TestContainerInterface;

#[Accessible]
final class Container extends BaseAsyncSingleton implements RollbackInterface
{
    private static TestContainerInterface|null $replace = null;

    public static function get(string $id): mixed
    {
        if (self::$replace) {
            return self::$replace->get($id);
        }

        return BaseContainer::instance()->get($id);
    }

    public static function has(string $id): bool
    {
        if (self::$replace) {
            return self::$replace->has($id);
        }

        return BaseContainer::instance()->has($id);
    }

    public static function getContainer(): \App\Bootstrap\ContainerInterface
    {
        if (self::$replace) {
            return self::$replace->getContainer();
        }

        return BaseContainer::instance();
    }

    #[\Override]
    public static function rollback(): void
    {
        if (self::$replace) {
            self::$replace->rollback();
        } else {
            CoreContainer::rollback();
            ContainerFactory::rollback();
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(TestContainerInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
