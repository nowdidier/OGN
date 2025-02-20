<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\SettingInterface;

#[Accessible]
final class Settings extends BaseSingleton
{
    private static SettingInterface|null $replace = null;

    public static function isStandardMode(): bool
    {
        if (self::$replace) {
            return self::$replace->isStandardMode();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->isStandardMode();
    }

    public static function isAsync(): bool
    {
        if (self::$replace) {
            return self::$replace->isAsync();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->isAsync();
    }

    public static function isCli(): bool
    {
        if (self::$replace) {
            return self::$replace->isCli();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->isCli();
    }

    public static function isDebug(): bool
    {
        if (self::$replace) {
            return self::$replace->isDebug();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->isDebug();
    }

    public static function getRealPath(string $keyOrPath): false|string
    {
        if (self::$replace) {
            return self::$replace->getRealPath($keyOrPath);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getRealPath($keyOrPath);
    }

    public static function getPath(string $keyOrPath): false|string
    {
        if (self::$replace) {
            return self::$replace->getPath($keyOrPath);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getPath($keyOrPath);
    }

    public static function isEndingUrl(): bool
    {
        if (self::$replace) {
            return self::$replace->isEndingUrl();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->isEndingUrl();
    }

    public static function getParam(string $name, string $key): mixed
    {
        if (self::$replace) {
            return self::$replace->getParam($name, $key);
        }
        return BaseContainer::instance()->get(SettingInterface::class)->getParam($name, $key);
    }

    public static function common(string $key): mixed
    {
        if (self::$replace) {
            return self::$replace->common($key);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->common($key);
    }

    public static function main(string $key): mixed
    {
        if (self::$replace) {
            return self::$replace->main($key);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->main($key);
    }

    public static function database(string $key): mixed
    {
        if (self::$replace) {
            return self::$replace->database($key);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->database($key);
    }

    public static function system(string $key): mixed
    {
        if (self::$replace) {
            return self::$replace->system($key);
        }

        return BaseContainer::instance()->get(SettingInterface::class)->system($key);
    }

    public static function getModuleName(): ?string
    {
        if (self::$replace) {
            return self::$replace->getModuleName();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getModuleName();
    }

    public static function getDefaultLang(): string
    {
        if (self::$replace) {
            return self::$replace->getDefaultLang();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getDefaultLang();
    }

    public static function getAllowedLanguages(): array
    {
        if (self::$replace) {
            return self::$replace->getAllowedLanguages();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getAllowedLanguages();
    }

    public static function getAutodetectLang(): string
    {
        if (self::$replace) {
            return self::$replace->getAutodetectLang();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getAutodetectLang();
    }

    public static function getControllerMethodName(): ?string
    {
        if (self::$replace) {
            return self::$replace->getControllerMethodName();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getControllerMethodName();
    }

    public static function getInitialRequest(): object
    {
        if (self::$replace) {
            return self::$replace->getInitialRequest();
        }

        return BaseContainer::instance()->get(SettingInterface::class)->getInitialRequest();
    }

    #[ForTestOnly]
    public static function replaceWithMock(SettingInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
