<?php


namespace Hleb\Static;

use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\Reference\ViewInterface;

final class View
{
    private static ViewInterface|null $replace = null;

    public static function view(string $template, array $params = [], ?int $status = null): \Hleb\Constructor\Data\View
    {
        if (self::$replace) {
            return self::$replace->view($template, $params, $status);
        }

        $template = \str_replace('\\', '/', trim($template, '/\\'));

        return new \Hleb\Constructor\Data\View($template, $params, $status);
    }

    #[ForTestOnly]
    public static function replaceWithMock(ViewInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
