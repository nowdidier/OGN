<?php


namespace Hleb\Constructor\Cache;

use Hleb\DynamicStateException;

final class OnceResult
{
    private static array $data = [];

    public static function get(callable $func): mixed
    {
        $backtrace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $pos = $backtrace[2] ?? null;

        if (empty($pos) || $pos['function'] !== 'once') {
            throw new DynamicStateException('Incorrect use of the once() function');
        }

        $key = $pos['file'] . ':' . $pos['line'];

        if (\array_key_exists($key, self::$data)) {
            return self::$data[$key];
        }

        return self::$data[$key] = $func();
    }

    public static function rollback(): void
    {
        self::$data = [];
    }
}
