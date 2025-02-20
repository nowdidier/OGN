<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;

use Hleb\Route\Group\EndGroup;
use Route;

trait GroupTrait
{

    public function group(callable $fn): EndGroup
    {
        $fn();

        return new EndGroup();
    }
}
