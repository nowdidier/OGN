<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupNoDebug;

trait GroupNoDebugTrait
{

    public function noDebug(): GroupNoDebug
    {
        return new GroupNoDebug();
    }
}
