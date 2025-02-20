<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Database\PdoManager;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\DbInterface;
use PDO;

#[Accessible]
final class DB extends BaseSingleton
{
    private static DbInterface|null $replace = null;

    public static function run(#[\SensitiveParameter] string $sql, #[\SensitiveParameter] array $args = [], ?string $configKey = null): false|\PDOStatement
    {
        if (self::$replace) {
            return self::$replace->run($sql, $args, $configKey);
        }

        return BaseContainer::instance()->get(DbInterface::class)->run($sql, $args, $configKey);
    }

    public static function dbQuery(#[\SensitiveParameter] string $sql, ?string $configKey = null): false|array
    {
        if (self::$replace) {
            return self::$replace->dbQuery($sql, $configKey);
        }

        return BaseContainer::instance()->get(DbInterface::class)->dbQuery($sql, $configKey);
    }

    public static function getPdoInstance(?string $configKey = null): PdoManager
    {
        if (self::$replace) {
            return self::$replace->getPdoInstance($configKey);
        }

        return BaseContainer::instance()->get(DbInterface::class)->getPdoInstance($configKey);
    }

    public static function getNewInstance(?string $configKey = null): PDO
    {
        if (self::$replace) {
            return self::$replace->getNewPdoInstance($configKey);
        }

        return BaseContainer::instance()->get(DbInterface::class)->getNewPdoInstance($configKey);
    }

    public static function getConfig(?string $configKey = null): ?array
    {
        if (self::$replace) {
            return self::$replace->getConfig($configKey);
        }

        return BaseContainer::instance()->get(DbInterface::class)->getConfig($configKey);
    }

    public static function quote(string $value, int $type = PDO::PARAM_STR, ?string $config = null): string
    {
        if (self::$replace) {
            return self::$replace->quote($value, $type, $config);
        }

        return BaseContainer::instance()->get(DbInterface::class)->quote($value, $type, $config);
    }

    #[ForTestOnly]
    public static function replaceWithMock(DbInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
