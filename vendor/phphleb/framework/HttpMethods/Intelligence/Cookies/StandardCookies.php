<?php


namespace Hleb\HttpMethods\Intelligence\Cookies;

use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\BaseAsyncSingleton;

final class StandardCookies extends BaseAsyncSingleton implements CookieInterface
{
    private static array $data = [];

    private static array $options = [];

    #[\Override]
    public static function set(string $name, string $value, array $options = []): void
    {
        self::$data[$name] = $value;

        self::$options[$name] = $options;

        \setcookie($name, $value, $options);
    }

    #[\Override]
    public static function get(string $name): DataType
    {
        return new DataType(self::$data[$name] ?? null);
    }

    #[\Override]
    public static function all(): array
    {
        $data = [];
        foreach (self::$data as $key => $value) {
            $data[$key] = new DataType($value);
        }
        return $data;
    }

    #[\Override]
    public static function setSessionName(string $name): void
    {
        \session_name($name);
    }

    #[\Override]
    public static function getSessionName(): string
    {
        return \session_name();
    }

    #[\Override]
    public static function setSessionId(string $id): void
    {
        \session_id($id);
    }

    #[\Override]
    public static function getSessionId(): string
    {
        return (string)\session_id();
    }

    #[\Override]
    public static function delete(string $name): void
    {
        if (isset(self::$data[$name])) {
            $options = self::$options[$name] ?? [];
            unset($_COOKIE[$name], self::$options[$name], self::$data[$name]);
            $options['expires'] = \time() - 31536000;
            isset($options['path']) or $options['path'] = '/';

            \setcookie($name, '', $options);
        }
    }

    #[\Override]
    public static function clear(): void
    {
        foreach (\array_keys(self::$data) as $name) {
            self::delete($name);
        }
    }

    #[\Override]
    public static function output(): void
    {
        self::rollback();
    }

    public static function sync(): void
    {
        foreach ($_COOKIE ?? [] as $key => $value) {
            self::$data[$key] = $value;
        }
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$data = [];

        self::$options = [];
    }
}
