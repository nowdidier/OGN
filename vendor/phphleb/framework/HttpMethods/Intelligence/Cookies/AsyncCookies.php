<?php


namespace Hleb\HttpMethods\Intelligence\Cookies;

use Hleb\Base\RollbackInterface;
use Hleb\HttpMethods\Intelligence\AsyncConsolidator;
use Hleb\Static\Response;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Static\Settings;

final class AsyncCookies extends BaseAsyncSingleton implements CookieInterface, RollbackInterface
{

    private static array $data = [];

    private static string $sessionName = 'PHPSESSID';

    private static array $deleteList = [];

    #[\Override]
    public static function get(string $name): DataType
    {
        $cookie = self::$data[$name] ?? null;
        if (\is_array($cookie)) {
            $cookie = $cookie['value'];
        }
        return new DataType($cookie);
    }

    #[\Override]
    public static function set(string $name, string $value, array $options = []): void
    {
        self::$data[$name] = AsyncConsolidator::convertCookie($name, $value, $options);
        unset(self::$deleteList[$name]);
    }

    public static function setOptions(array $data): void
    {
        self::$data = [];
        self::$deleteList = [];
        foreach ($data as $id => $cookie) {

            if (\is_string($id)) {
                self::$data[$id] = $cookie;
                continue;
            }
            if (\is_array($cookie)) {
                $body = \explode('=', \array_shift($cookie));
                $name = \array_shift($body);
                if ($body) {
                    self::$data[$name] = \implode('=', $body);
                }
                continue;
            }
            if (\is_string($cookie)) {
                $cookies = \str_contains($cookie, ';') ? \array_map('ltrim', \explode(';', $cookie)) : [$cookie];
                foreach ($cookies as $block) {
                    $body = \explode('=', $block);
                    $name = \array_shift($body);
                    if ($body) {
                        $body = \implode('=', $body);


                        isset(self::$data[$name]) or self::$data[$name] = $body;
                    }
                }
            }
        }
    }

    #[\Override]
    public static function all(): array
    {
        $data = [];
        foreach (self::$data as $key => $value) {
            if (\is_array($value)) {
                $value = $value['value'];
            }
            $data[$key] = new DataType($value);
        }
        return $data;
    }

    #[\Override]
    public static function setSessionName(string $name): void
    {
        if (isset(self::$data[self::$sessionName]) && $name !== self::$sessionName) {
            self::$data[$name] = self::$data[self::$sessionName];
            self::$deleteList[self::$sessionName] = true;
            unset(
                self::$data[self::$sessionName],
                self::$deleteList[$name],
            );
        }
        self::$sessionName = $name;
    }

    #[\Override]
    public static function getSessionName(): string
    {
        return self::$sessionName;
    }

    #[\Override]
    public static function setSessionId(string $id): void
    {
        unset(self::$deleteList[self::$sessionName]);

        $options = Settings::getParam('main', 'session.options');
        if ($options) {
            self::set(self::$sessionName, $options);
            return;
        }
        $params = [
            'value' => $id,
            'Path' => '/',
            'SameSite' => 'Strict',
        ];
        $lifetime = Settings::getParam('system', 'max.session.lifetime');
        if ($lifetime) {
            $params['Expires'] = \date('D, d M Y H:i:s \G\M\T', \time() + $lifetime);
        }
        self::$data[self::$sessionName] = $params;
    }

    #[\Override]
    public static function getSessionId(): string
    {
        return self::get(self::$sessionName)->asString('');
    }

    #[\Override]
    public static function delete(string $name): void
    {
        self::$deleteList[$name] = true;
        unset(self::$data[$name]);
    }

    #[\Override]
    public static function output(): void
    {
        foreach (self::$data as $name => $data) {


            if (!\is_array($data)) {
                continue;
            }
            $cookie = [];
            foreach ($data as $param => $value) {
                if ($param === 'value') {
                    $cookie[] = $name . '=' . $value;
                    continue;
                }
                if ($value === true) {
                    $cookie[] = $param;
                    continue;
                }
                $cookie[] = $param . '=' . $value;
            }
            self::update($cookie);
        }
        $expires = \date('D, d M Y H:i:s \G\M\T', \time() - 31536000);
        foreach (self::$deleteList as $name => $data) {
            if (\is_array($data)) {
                $cookie = [];
                foreach ($data as $param => $value) {
                    if ($param === 'value') {
                        $cookie[] = $name . '=';
                        continue;
                    }
                    if ($param === 'Expires') {
                        continue;
                    }
                    if ($value === true) {
                        $cookie[] = $param;
                        continue;
                    }
                    $cookie[] = $param . '=' . $value;
                }
                $cookie[] = 'Expires=' . $expires;
                self::update($cookie);
                if (!in_array('Path', $data)) {
                    $cookie[] = 'Path=/';
                    self::update($cookie);
                }
            } else {
                $cookie = [$name . '=', 'Expires=' . $expires];
                self::update($cookie);
                $cookie[] = 'Path=/';
                self::update($cookie);
            }
        }
        self::rollback();
    }

    #[\Override]
    public static function clear(): void
    {
        foreach (self::$data as $name => $item) {
            self::delete($name);
        }
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$data = [];

        self::$deleteList = [];

        self::$sessionName = 'PHPSESSID';
    }

    private static function update(array$cookie): void
    {
        Response::addHeaders(['Set-Cookie' => \implode('; ', $cookie)], false);
    }
}
