<?php

declare(strict_types=1);

namespace Hleb\Main\Console;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Static\Settings;

#[Accessible]
class Colorizer
{

    final public function __construct() {
    }

    public static function standard(string $text): string
    {
        return self::checkAndColorize("\e[0m", "\e[0m", $text);
    }

    public static function red(string $text): string
    {
        return self::checkAndColorize("\e[31;1m", "\e[0m", $text);
    }

    public static function green(string $text): string
    {
        return self::checkAndColorize("\e[32;1m", "\e[0m", $text);
    }

    public static function cyan(string $text): string
    {
        return self::checkAndColorize("\e[36;1m", "\e[0m", $text);
    }

    public static function yellow(string $text): string
    {
        return self::checkAndColorize("\e[33m", "\e[0m", $text);
    }

    public static function error(string $text): string
    {
        return self::checkAndColorize("\e[41;37;1m", "\e[0m", $text);
    }

    public static function errorMessage(string $text): string
    {
        return PHP_EOL . self::checkAndColorize("\e[41;37;1m", "\e[0m", " " . \trim($text) . " ") . PHP_EOL;
    }

    public static function success(string $text): string
    {
        return self::checkAndColorize("\e[37;42;1m", "\e[0m", $text);
    }

    public static function successMessage(string $text): string
    {
        return PHP_EOL . self::checkAndColorize("\e[37;42;1m", "\e[0m", " " . \trim($text) . " ") . PHP_EOL;
    }

    public static function blue(string $text): string
    {
        return self::checkAndColorize("\e[34;1m", "\e[0m", $text);
    }

    protected static function checkAndColorize(string $start, string $end, string $baseText): string
    {
       if (self::isColorSupported() && Settings::isCli()) {
           if (\str_ends_with($baseText, ' ')) {
               $endSpaces = '';
               if (\preg_match('/^(.*?)(\s*)$/', $baseText, $matches)) {
                   $baseText = $matches[1];
                   $endSpaces = $matches[2];
               }
               return $start . $baseText . $end . $start . $endSpaces . $end;
           }
            return $start . $baseText . $end;
       }
       return $baseText;
    }

    protected static function isColorSupported(): bool
    {
        if (self::isWindows()) {
            if (self::isWindows10OrHigher()) {
                return true;
            }
            return false;
        } else {
            $term = getenv('TERM');
            return $term && (
                    \str_contains($term, 'xterm') ||
                    \str_contains($term, 'color') ||
                    $term === 'linux'
                );
        }
    }

    private static function isWindows(): bool
    {
        return \strtoupper(\substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private static function isWindows10OrHigher(): bool|int
    {
        if (!self::isWindows()) {
            return false;
        }

        $ver = \explode('.', \php_uname('r'));
        while (\count($ver) < 3) {
            $ver[] = '0';
        }
        $ver = \implode('.', \array_slice($ver, 0, 3));

        return \version_compare($ver, '10.0.0', '>=');
    }
}
