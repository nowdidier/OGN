<?php


namespace Hleb\Constructor\Data;

use Hleb\DynamicStateException;
use Hleb\HlebBootstrap;
use Hleb\Main\Insert\BaseSingleton;

final class SystemSettings extends BaseSingleton
{
    private static ?array $data = null;

    private static ?int $mode = null;

    private static ?array $argv = null;

    public static function init(int $mode = HlebBootstrap::STANDARD_MODE): void
    {
        self::$mode = $mode;
    }

    public static function setData(array $data): void
    {
        self::$data = $data;
        if (isset($data['database'])) {
            self::$data['default.database'] = $data['database'];
        }
    }

    public static function getData(): array
    {
        return self::$data ?? [];
    }

    public static function setSuite(string $name, array $data): void
    {
        self::$data[$name] = $data;
    }

    public static function setValue(string $name, string $key, null|string|float|array|int|bool $value): void
    {
        self::$data[$name][$key] = $value;
    }

    public static function getValue(string $name, string $key): null|string|array|float|int|bool
    {
        return self::$data[$name][$key] ?? null;
    }

    public static function getSystemValue(string $key): null|string|array|float|int|bool
    {
        return self::$data['system'][$key] ?? null;
    }

    public static function getMainValue(string $key): null|string|array|float|int|bool
    {
        return self::$data['main'][$key] ?? null;
    }

    public static function getCommonValue(string $key): null|string|array|float|int|bool
    {
        return self::$data['common'][$key] ?? null;
    }

    public static function isStandardMode(): bool
    {
        return self::$mode === HlebBootstrap::STANDARD_MODE;
    }

    public static function isAsync(): bool
    {
        return self::$mode === HlebBootstrap::ASYNC_MODE;
    }

    public static function isCli(): bool
    {
        return self::$mode === HlebBootstrap::CONSOLE_MODE;
    }

    public static function getAlias(string $keyOrPath, bool $ifExists = true): false|string
    {
        if (!\str_starts_with($keyOrPath, '@')) {
            if ($result = self::getValue('path', $keyOrPath)) {
                return $result;
            }
            throw new DynamicStateException("The `$keyOrPath` value was not found in the valid file path abbreviations.");
        }


        if (\str_contains($keyOrPath, '..')) {
            throw new DynamicStateException("You cannot use '...' in file path abbreviations: $keyOrPath");
        }
        $keyOrPath = str_replace('\\', '/', $keyOrPath);
        $dir = \strstr($keyOrPath, '/', true) ?: $keyOrPath;
        $path = \strstr($keyOrPath, '/');
        $path = $path ?: '';

        $path = match ($dir) {
            '@', '@global' => self::getValue('path', 'global') . $path,
            '@public' => self::getValue('path', 'public') . $path,
            '@storage' => self::getValue('path', 'storage') . $path,
            '@resources' => self::getValue('path', 'resources') . $path,
            '@app' => self::getValue('path', 'app') . $path,
            '@views' => self::getValue('path', 'views') . $path,
            '@modules' => self::getValue('path', 'modules') . $path,
            '@vendor' => self::getValue('path', 'vendor') . $path,
            '@library' => self::getValue('path', 'library') . $path,
            '@framework' => self::getValue('path', 'framework') . $path,
            default => false,
        };
        if (!$path) {
            throw new DynamicStateException("The `@$keyOrPath` value was not found in the valid file path abbreviations.");
        }


        return $ifExists ? \realpath($path) . (\str_ends_with($path, '/') ? DIRECTORY_SEPARATOR : '') : $path;
    }

    public static function getRealPath(string $keyOrPath): false|string
    {
        return self::getAlias($keyOrPath);
    }

    public static function getPath(string $keyOrPath): string
    {
        return (string)self::getAlias($keyOrPath, ifExists:false);
    }

    public static function getLogOn(): bool
    {
        return (bool)self::getCommonValue('log.enabled');
    }

    public static function getSortLog(): bool
    {
        return (bool)self::getCommonValue( 'log.sort');
    }

    public static function getArgv(): array
    {
        return self::$argv ?? [];
    }

    public static function updateMainSettings(array $data): void
    {
        self::$data['main'] = \array_merge(self::$data['main'] ?? [], $data);
    }

    public static function addModuleType(bool $type): void
    {
        self::$data['main']['module.view.type'] = $type ? 'closed' : 'opened';
    }

    public static function updateDatabaseSettings(array $data): void
    {
        $settings = $data['db.settings.list'] ?? [];
        $oldSettings = self::$data['database']['db.settings.list'] ?? [];
        self::$data['database'] = \array_merge(self::$data['database'] ?? [], $data);
        self::$data['database']['db.settings.list'] = \array_merge($oldSettings, $settings);
    }

    public static function setStartTime(float $time): void
    {
        self::$data['system']['start.unixtime'] = $time;
    }

}
