<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\LogInterface;
use Hleb\Main\Logger\Log as Logger;

#[Accessible]
final class Log extends BaseSingleton
{
    private static LogInterface|null $replace = null;

    public static function emergency(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->emergency($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->emergency($message, self::b7e($context));
        }
    }

    public static function alert(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->alert($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->alert($message, self::b7e($context));
        }
    }

    public static function critical(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->critical($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->critical($message, self::b7e($context));
        }
    }

    public static function error($message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->error($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->error($message, self::b7e($context));
        }
    }

    public static function warning(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->warning($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->warning($message, self::b7e($context));
        }
    }

    public static function notice(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->notice($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->notice($message, self::b7e($context));
        }
    }

    public static function info(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->info($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->info($message, self::b7e($context));
        }
    }

    public static function debug(string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->debug($message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->debug($message, self::b7e($context));
        }
    }

    public static function log($level, string $message, array $context = []): void
    {
        if (self::$replace) {
            self::$replace->log($level, $message, $context);
        } else {
            BaseContainer::instance()->get(LogInterface::class)->log($level, $message, self::b7e($context));
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(LogInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }

    private static function b7e(array $context): array
    {
        if (empty($context[Logger::B7E_NAME])) {
            $context[Logger::B7E_NAME] = Logger::STATIC_B7E;
        }

        return $context;
    }
}
