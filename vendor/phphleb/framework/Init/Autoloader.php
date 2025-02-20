<?php


namespace Hleb\Init;

use Hleb\Init\Connectors\HlebConnector;
use Hleb\Init\Connectors\PhphlebConnector;

final class Autoloader
{
    private const LIBRARY_NAMES = ['XdORM'];

    private static string $vendorPath;

    private static string $frameworkPath;

    private static ?string $globalPath = null;

    private static bool $singleCall = true;

    public static function init(
        string $vendorPath,
        string $globalPath,
        bool   $singleCall = true,
    ): void
    {
        self::$vendorPath = $vendorPath;
        self::$globalPath = $globalPath;
        self::$frameworkPath = $vendorPath . '/phphleb/framework';
        self::$singleCall = $singleCall;
    }

    public static function makeStatic(string $class): string|false
    {
        if (isset(HlebConnector::$formattedMap[$class])) {
            return self::searchFile($class, HlebConnector::$formattedMap, self::$frameworkPath);
        }
        $element = \strstr($class, '\\', true);
        if ($element === 'Hleb') {
            if (\str_ends_with($class, 'Exception')) {
                return self::searchFile($class, HlebConnector::$exceptionMap, self::$frameworkPath);
            }
            return self::searchFile($class, HlebConnector::$map, self::$frameworkPath);
        }
        if ($element === 'Phphleb') {
            return self::searchFile($class, PhphlebConnector::$map);
        }
        if (\in_array($element, self::LIBRARY_NAMES, true)) {
            return self::searchFile($class, HlebConnector::$libraryMap);
        }
        if ($element === 'App') {
            if (\str_starts_with($class, 'App\Bootstrap')) {
                return self::searchFile($class, HlebConnector::$bootstrapMap, self::$globalPath);
            }


            return self::searchFile($class, HlebConnector::$anyMap, self::$globalPath);
        }
        return false;
    }

    public static function searchFile(string $class, array &$data, ?string $path = null): string|false
    {
        if (isset($data[$class])) {
            $classSubPath = $data[$class];


            if (self::$singleCall) {
                unset($data[$class]);
            }
            return ($path ?? self::$vendorPath) . $classSubPath;
        }

        return false;
    }

    public static function makeCustom(string $class): string|false
    {
        return CustomAutoloader::make($class, self::$globalPath, self::$vendorPath);
    }
}
