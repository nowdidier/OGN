<?php

namespace Hleb\Init\ShootOneselfInTheFoot;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;

#[ForTestOnly] #[Accessible]
final class Mock extends BaseMockAddOn
{

    #[ForTestOnly]
    #[\Override]
    public static function cancel(): void
    {

        foreach ([
                     ArrForTest::class,
                     CacheForTest::class,
                     ContainerForTest::class,
                     CommandForTest::class,
                     CookiesForTest::class,
                     CsrfForTest::class,
                     DbForTest::class,
                     DebugForTest::class,
                     LogForTest::class,
                     PatchForTest::class,
                     RedirectForTest::class,
                     ScriptForTest::class,
                     RequestForTest::class,
                     ResponseForTest::class,
                     RouterForTest::class,
                     SessionForTest::class,
                     SettingForTest::class,
                     SystemForTest::class,
                     TemplateForTest::class,
                     ViewForTest::class,
                     ConverterForTest::class,
                     DiForTest::class,
                     OnceForTest::class,
                 ] as $item) {
            $item::cancel();
        }
    }
}
