<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;

use Hleb\Route\NoDebug;

trait InsertNoDebugTrait
{

    public function noDebug(): NoDebug
    {
        return new NoDebug();
    }
}
