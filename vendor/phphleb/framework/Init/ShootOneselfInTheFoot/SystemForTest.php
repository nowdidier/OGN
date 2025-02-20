<?php

declare(strict_types=1);

namespace Hleb\Init\ShootOneselfInTheFoot;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\Reference\SystemInterface;
use Hleb\Static\System;

#[ForTestOnly] #[Accessible]
final class SystemForTest extends BaseMockAddOn
{
    #[ForTestOnly]
   public static function set(SystemInterface $mock): void
   {
       System::replaceWithMock($mock);
   }

    #[ForTestOnly]
    #[\Override]
    public static function cancel(): void
    {
        System::replaceWithMock(null);
    }
}
