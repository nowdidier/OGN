<?php

declare(strict_types=1);

namespace Hleb\Main\Info;

final class PathInfoDoc
{

    public static function special(string $public = 'public', string $vendor = 'vendor'): array
    {
        return [

            '@' => '/',
            '@global' => '/',

            '@app' => '/app',

            '@public' => "/$public",

            '@storage' => '/storage',

            '@resources' => '/resources',

            '@views' => '/resources/views',

            '@modules' => '/modules',

            '@vendor' => "/$vendor",

            '@library' => "/$vendor/phphleb",

            '@framework' => "/$vendor/phphleb/framework",
        ];
    }
}
