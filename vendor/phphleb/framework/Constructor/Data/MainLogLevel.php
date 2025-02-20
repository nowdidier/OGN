<?php


namespace Hleb\Constructor\Data;

use Hleb\Base\RollbackInterface;
use Hleb\InvalidArgumentException;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Main\Logger\LogLevel;

final class MainLogLevel extends BaseAsyncSingleton implements RollbackInterface
{
    private const LEVEL_ERROR = 'Invalid level value. Possible:';

    private const PERMISSION_ERROR = 'The `log.level.in-cli` setting in the `common` configuration does not allow changing the logging level.';

    private static ?string $path = null;

    private static ?string $level = null;

    public static function get(): string
    {
        if (self::$level) {
            return self::$level;
        }
        if (\file_exists(self::getPath())) {
            return self::$level = \file_get_contents(self::getPath());
        }
        return self::$level = SystemSettings::getCommonValue('max.log.level');
    }

    public static function set(string $level): string
    {
        if (!SystemSettings::getCommonValue('log.level.in-cli')) {
            throw new InvalidArgumentException(self::PERMISSION_ERROR);
        }
        if (!\in_array($level, LogLevel::ALL)) {
            throw new InvalidArgumentException(self::LEVEL_ERROR . ' ' . \implode(', ', LogLevel::ALL) . '.');
        }
        \hl_create_directory(self::getPath());
        \file_put_contents(self::getPath(), $level);
        @\chmod(self::getPath(), 0664);

        return self::get();
    }

    public static function setDefault(): string
    {
        \unlink(self::getPath());

        return self::get();
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$level = null;
    }

    private static function getPath(): string
    {
        if (!self::$path) {
            self::$path = SystemSettings::getPath('storage') . '/keys/global-log-level.key';
        }
        return self::$path;
    }
}
