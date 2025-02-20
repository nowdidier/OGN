<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Database\PdoManager;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\SystemInterface;

#[Accessible]
class System extends BaseSingleton
{
    private static SystemInterface|null $replace = null;

    public static function getRouteName(): ?string
    {
        if (self::$replace) {
            return self::$replace->getRouteName();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getRouteName();
    }

    public static function getActualLogLevel(): string
    {
        if (self::$replace) {
            return self::$replace->getActualLogLevel();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getActualLogLevel();
    }

    public static function getLogLevelList(): array
    {
        if (self::$replace) {
            return self::$replace->getLogLevelList();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getLogLevelList();
    }

    public static function getRouteCacheData(): array
    {
        if (self::$replace) {
            return self::$replace->getRouteCacheData();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getRouteCacheData();
    }

    public static function getRouteCacheInfo(): array
    {
        if (self::$replace) {
            return self::$replace->getRouteCacheInfo();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getRouteCacheInfo();
    }

    public static function getStartTime(): ?float
    {
        if (self::$replace) {
            return self::$replace->getStartTime();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getStartTime();
    }

    public static function getEndTime(): ?float
    {
        if (self::$replace) {
            return self::$replace->getEndTime();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getEndTime();
    }

    public static function getCoreEndTime(): ?float
    {
        if (self::$replace) {
            return self::$replace->getCoreEndTime();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getCoreEndTime();
    }

    public static function getRequestId(): string
    {
        if (self::$replace) {
            return self::$replace->getRequestId();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getRequestId();
    }

    public static function getLibraryKey(): string
    {
        if (self::$replace) {
            return self::$replace->getLibraryKey();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getLibraryKey();
    }

    public static function getDataFromDA(?string $key = null): array
    {
        if (self::$replace) {
            return self::$replace->getDataFromDA($key);
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getDataFromDA($key);
    }

    public static function getClassesAutoloadDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getClassesAutoloadDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getClassesAutoloadDataFromDA();
    }

    public static function getInsertTemplateDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getInsertTemplateDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getInsertTemplateDataFromDA();
    }

    public static function getMiddlewareDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getMiddlewareDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getMiddlewareDataFromDA();
    }

    public static function getInitiatorDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getInitiatorDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getInitiatorDataFromDA();
    }

    public static function getDebugDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getDebugDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getDebugDataFromDA();
    }

    public static function getHlCheckDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getHlCheckDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getHlCheckDataFromDA();
    }

    public static function getDbDebugDataFromDA(): array
    {
        if (self::$replace) {
            return self::$replace->getDbDebugDataFromDA();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getDbDebugDataFromDA();
    }

    public static function getHlebVersionAsConsoleFormat(): string
    {
        if (self::$replace) {
            return self::$replace->getHlebVersionAsConsoleFormat();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getHlebVersionAsConsoleFormat();
    }

    public static function getRoutesAsConsoleFormat(): string
    {
        if (self::$replace) {
            return self::$replace->getRoutesAsConsoleFormat();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getRoutesAsConsoleFormat();
    }

    public static function getPdoManager(#[\SensitiveParameter] ?string $configKey = null): PdoManager
    {
        if (self::$replace) {
            return self::$replace->getPdoManager($configKey);
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getPdoManager($configKey);
    }

    public static function updateRouteCacheAsConsoleFormat(): string
    {
        if (self::$replace) {
            return self::$replace->updateRouteCacheAsConsoleFormat();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->updateRouteCacheAsConsoleFormat();
    }

    public static function getFrameworkApiVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getFrameworkApiVersion();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getFrameworkApiVersion();
    }

    public static function getFrameworkVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getFrameworkVersion();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getFrameworkVersion();
    }

    public static function getVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getVersion();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getVersion();
    }

    public static function getApiVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getApiVersion();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getApiVersion();
    }

    public function getFrameworkResourcePrefix(): string
    {
        if (self::$replace) {
            return self::$replace->getFrameworkResourcePrefix();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->getFrameworkResourcePrefix();
    }

    public static function isWebConsoleActive(): bool
    {
        if (self::$replace) {
            return self::$replace->isWebConsoleActive();
        }

        return BaseContainer::instance()->get(SystemInterface::class)->isWebConsoleActive();
    }

    public static function createSqlQueryLog(
        float                          $startTime,
        #[\SensitiveParameter] string  $query,
        #[\SensitiveParameter] ?string $configKey = null,
        string                         $tag = 'special',
        ?string                        $driver = null,
    ): void {
        if (self::$replace) {
            self::$replace->createSqlQueryLog($startTime, $query, $configKey, $tag, $driver);
        } else {
            BaseContainer::instance()->get(SystemInterface::class)->createSqlQueryLog($startTime, $query, $configKey, $tag, $driver);
        }
    }

    public static function createCustomLog(
        #[\SensitiveParameter] string $sql,
        float $microtime,
        array $params = [],
        ?string $dbname = null,
        ?string $driver = null,
    ): void {
        if (self::$replace) {
            self::$replace->createCustomLog($sql, $microtime, $params, $dbname, $driver);
        } else {
            BaseContainer::instance()->get(SystemInterface::class)->createCustomLog($sql, $microtime, $params, $dbname, $driver);
        }
    }

    public static function getTaskPermissions(string $taskClass): array
    {
        if (self::$replace) {
            return self::$replace->getTaskPermissions($taskClass);
        }
        return BaseContainer::instance()->get(SystemInterface::class)->getTaskPermissions($taskClass);
    }

    #[ForTestOnly]
    public static function replaceWithMock(SystemInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
