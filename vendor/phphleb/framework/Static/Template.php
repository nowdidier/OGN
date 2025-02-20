<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\TemplateInterface;

#[Accessible]
final class Template extends BaseSingleton
{
    private static TemplateInterface|null $replace = null;

    public static function get(string $viewPath, array $extractParams = [], array $config = []): string
    {
        if (self::$replace) {
            return self::$replace->get($viewPath, $extractParams, $config);
        }

        return BaseContainer::instance()->get(TemplateInterface::class)->get($viewPath, $extractParams, $config);
    }

    public static function insert(string $viewPath, array $extractParams = [], array $config = []): void
    {
        if (self::$replace) {
            self::$replace->insert($viewPath, $extractParams, $config);
        } else {
            BaseContainer::instance()->get(TemplateInterface::class)->insert($viewPath, $extractParams, $config);
        }
    }

    public static function insertCache(string $viewPath, array $extractParams = [], int $sec = Cache::DEFAULT_TIME, array $config = []): void
    {
        if (self::$replace) {
            self::$replace->insertCache($viewPath, $extractParams, $sec, $config);
        } else {
            BaseContainer::instance()->get(TemplateInterface::class)->insertCache($viewPath, $extractParams, $sec, $config);
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(TemplateInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
