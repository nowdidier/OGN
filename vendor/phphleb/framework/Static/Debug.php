<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Reference\DebugInterface;

#[Accessible]
final class Debug extends BaseAsyncSingleton implements RollbackInterface
{
    private static DebugInterface|null $replace = null;

    public static function send(mixed $data, ?string $name = null): void
    {
        if (self::$replace) {
            self::$replace->send($data, $name);
        } else {
            BaseContainer::instance()->get(DebugInterface::class)->send($data, $name);
        }
    }

    public static function getCollection(): array
    {
        if (self::$replace) {
            return self::$replace->getCollection();
        }
        return BaseContainer::instance()->get(DebugInterface::class)->getCollection();
    }

    public static function setHlCheck(string $message, ?string $file = null, ?int $line = null): void
    {
        if (self::$replace) {
            self::$replace->setHlCheck($message, $file, $line);
        } else {
            BaseContainer::instance()->get(DebugInterface::class)->setHlCheck($message, $file, $line);
        }
    }

    public static function isActive(): bool
    {
        if (self::$replace) {
            return self::$replace->isActive();
        }
        return BaseContainer::instance()->get(DebugInterface::class)->isActive();
    }

    #[\Override]
    public static function rollback(): void
    {
        if (self::$replace) {
            self::$replace::rollback();
        } else {
            BaseContainer::instance()->get(DebugInterface::class)::rollback();
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(DebugInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
