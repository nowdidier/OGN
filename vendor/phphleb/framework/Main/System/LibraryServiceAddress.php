<?php

namespace Hleb\Main\System;

use Hleb\Constructor\Data\DynamicParams;

final class LibraryServiceAddress
{
    final public const KEY = 'hlresource';

    public static function getFullAddress(string $library, string $version = 'v1'): string
    {
        $uri = DynamicParams::getRequest()->getUri();
        return $uri->getScheme() . '://' . $uri->getHost() . self::getAddress($library, $version);
    }

    public static function getAddress(string $library, string $version = 'v1'): string
    {
        return '/' . self::KEY . '/' . $library . '/' . $version;
    }
}
