<?php


namespace Hleb\Helpers;

use Hleb\DomainException;
use Throwable;

final class DefaultValueHelper
{

    public static function err(bool|string $exc, string $text = 'A required parameter is missing.'): void
    {
        if ($exc === false) {
            return;
        }
        if ($exc === true) {
            throw new DomainException($text);
        }
        if (\class_exists($exc) && \is_subclass_of($exc, Throwable::class)) {
            throw new $exc($text);
        }
        throw new \RuntimeException("The class $exc should be an exception.");
    }
}
