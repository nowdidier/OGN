<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class HostHelper
{

    public static function isLocalhost(string $host): bool
    {
        if (\in_array($host, ['localhost', '127.0.0.1', '::1'])) {
            return true;
        }
        if (\str_starts_with($host, 'localhost:')) {
            return true;
        }
        if (\str_starts_with($host, '127.0.0.')) {
            return true;
        }
        return false;
    }
}
