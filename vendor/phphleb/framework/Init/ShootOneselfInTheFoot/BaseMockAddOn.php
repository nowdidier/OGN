<?php

declare(strict_types=1);

namespace Hleb\Init\ShootOneselfInTheFoot;

use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\Main\Insert\BaseSingleton;

abstract class BaseMockAddOn extends BaseSingleton
{

    #[ForTestOnly]
    abstract public static function cancel(): void;
}
