<?php

declare(strict_types=1);

namespace Hleb\Constructor\Models;

use App\Bootstrap\ContainerInterface;
use Hleb\Reference\SettingInterface;
use Hleb\Static\Container;

trait ModelTrait
{

    final protected static function container(): ContainerInterface
    {
        return Container::getContainer();
    }

    final protected static function settings(): SettingInterface
    {
        return self::container()->get(SettingInterface::class);
    }
}
