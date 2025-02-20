<?php

namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\ConverterInterface;
use Phphleb\PsrAdapter\Psr11\IntermediateContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class Converter extends BaseSingleton
{
    private static ConverterInterface|null $replace = null;

    public static function toPsr11Container(): \Psr\Container\ContainerInterface
    {
        if (self::$replace) {
            return self::$replace->toPsr11Container();
        }

        return BaseContainer::instance()->get(ConverterInterface::class)->toPsr11Container();
    }

    public static function toPsr3Logger(): LoggerInterface
    {
        if (self::$replace) {
            return self::$replace->toPsr3Logger();
        }

        return BaseContainer::instance()->get(ConverterInterface::class)->toPsr3Logger();
    }

    public static function toPsr16SimpleCache(): CacheInterface
    {
        if (self::$replace) {
            return self::$replace->toPsr16SimpleCache();
        }

        return BaseContainer::instance()->get(ConverterInterface::class)->toPsr16SimpleCache();
    }

    #[ForTestOnly]
    public static function replaceWithMock(ConverterInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
