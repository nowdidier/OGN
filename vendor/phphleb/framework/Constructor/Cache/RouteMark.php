<?php


namespace Hleb\Constructor\Cache;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseAsyncSingleton;

final class RouteMark extends BaseAsyncSingleton implements RollbackInterface
{
    final public const INFO_CLASS_NAME = 'HL2Info';

    final public const PREVIEW_PREFIX = 'HL2PreviewCache';

    final public const DATA_PREFIX = 'HL2';

    private const CACHE_CLASS_NAME = 'HL2ConfigHash';

    private static ?string $hash = null;

    public static function getHash(): false|string
    {
        return self::$hash ?? self::$hash = self::getFromFile();
    }

    public static function getRouteClassName(string $name): string
    {
        return $name . '_' . self::getHash();
    }

    public static function generateHash(array $data): bool
    {
        self::deleteOldHash();

        $dataHash = self::createHash($data);
        $dir = SystemSettings::getRealPath('storage') . '/cache/routes/';
        \hl_create_directory($dir);
        $class = self::CACHE_CLASS_NAME;
        $content = "<?php

final class {$class}
{  

   public const HASH = '{$dataHash}';
}
";
        $path = $dir . $class. '.php';
        \file_put_contents($path, $content, LOCK_EX);
        @\chmod($path, 0664);
        if (empty(\file_get_contents($path))) {
            throw new CoreProcessException('Failed to save route cache key.');
        }

        return \file_exists($path);
    }

    private static function getFromFile(): false|string
    {
        $dir = SystemSettings::getRealPath('@storage/cache/routes/');
        if (!$dir) {
            return false;
        }
        $class = self::CACHE_CLASS_NAME;
        $file = $dir . $class . '.php';
        if (!\class_exists($class, false)) {
            if (!\file_exists($file)) {
                if (!\file_exists($dir . 'Map')) {
                    return false;
                }
                \usleep(10000);
                if (!\file_exists($file)) {
                    return false;
                }
            }
            require $file;
        }

        return $class::HASH;
    }

    private static function createHash(array $data): string
    {
        $data = \json_encode($data);
        $length = (string)\strlen($data);
        $hash = \sha1($data);

        self::$hash = \substr($hash, 0, 4) . \substr(\sha1($hash), 0, 5) . $length;

        return self::$hash;
    }

    #[\Override]
    public static function rollback(): void
    {

    }

    private static function deleteOldHash(): void
    {
        foreach (self::getFiles() ?: [] as $file) {
            if (!\is_dir($file)) {
                \unlink($file);
            }
        }
    }

    private static function getFiles(): false|array
    {
        $dir = SystemSettings::getRealPath('@storage/cache/routes/');
        if (!$dir) {
            return false;
        }
        $result = [];
        $files = (array)\scandir($dir);
        foreach($files ?: [] as $file) {
            if (\str_starts_with($file, self::INFO_CLASS_NAME)) {
                $result[] = $dir . $file;
            }
        }
        if (\count($result) > 1) {
            \usort($result, static function ($a, $b) {
                return \filemtime($a) <=> \filemtime($b);
            });
        }

        return $result;
    }
}
