<?php

declare(strict_types=1);

namespace Hleb\Init\ShootOneselfInTheFoot;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\Reference\CsrfInterface;
use Hleb\Static\Csrf;

#[ForTestOnly] #[Accessible]
final class CsrfForTest extends BaseMockAddOn
{
    #[ForTestOnly]
   public static function set(CsrfInterface $mock): void
   {
       Csrf::replaceWithMock($mock);
   }

    #[ForTestOnly]
    #[\Override]
    public static function cancel(): void
    {
        Csrf::replaceWithMock(null);
    }
}
