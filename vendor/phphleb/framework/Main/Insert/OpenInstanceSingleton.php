<?php


namespace Hleb\Main\Insert;

use Hleb\Constructor\Attributes\AvailableAsParent;

#[AvailableAsParent]
class OpenInstanceSingleton extends BaseSingleton
{

    final public static function instance(): static
    {
        return self::getInstance();
    }
}
