<?php


namespace Hleb\Static;

use AsyncExitException;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\HlebBootstrap;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\ScriptInterface;
use JetBrains\PhpStorm\NoReturn;

#[Accessible]
final class Script extends BaseSingleton
{
    private static ScriptInterface|null $replace = null;

    public static function asyncExit($message = '', ?int $httpStatus = null, array $headers = []): never
    {
        if (self::$replace) {
            self::$replace->asyncExit($message, $httpStatus, $headers);
        } else {
            try {
                $httpStatus = $httpStatus ?? Response::getStatus();
                Response::addHeaders($headers);
                $body = Response::getBody();
                $headers = Response::getHeaders();
            } catch (\Throwable) {


                $httpStatus = $httpStatus ?? 500;
                $body = $body ?? '';
            }
            $message = \is_int($message) ? $message : $body . $message;
            if (\defined('HLEB_LOAD_MODE') && HLEB_LOAD_MODE !== HlebBootstrap::ASYNC_MODE) {
                self::standardExit($message, $httpStatus, $headers);
            }
            $message = \is_int($message) ? '' : $message;
            throw (new AsyncExitException($message))->setHeaders($headers)->setStatus($httpStatus);
        }
    }

    #[NoReturn]
    public static function standardExit($message = '', int $httpCode = 200, array $headers = []): never
    {
        if (self::$replace) {
            self::$replace->standardExit($message, $httpCode, $headers);
        } else {
            \headers_sent() or \http_response_code($httpCode);
            foreach ($headers as $name => $header) {
                if (\is_array($header)) {
                    foreach ($header as $h) {
                        \header("$name: $h");
                    }
                } else {
                    \header("$name: $header");
                }
            }
            exit($message);
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(ScriptInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
