<?php

declare(strict_types=1);

namespace Hleb\Main\Console;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
class ReplacingColorizer extends Colorizer
{

    public static function standard(string $text): string
    {
        return $text;
    }

    public static function red(string $text): string
    {
        return $text;
    }

    public static function green(string $text): string
    {
        return $text;
    }

    public static function cyan(string $text): string
    {
        return $text;
    }

    public static function yellow(string $text): string
    {
        return $text;
    }

    public static function error(string $text): string
    {
        return $text;
    }

    public static function errorMessage(string $text): string
    {
        return $text;
    }

    public static function success(string $text): string
    {
        return $text;
    }

    public static function successMessage(string $text): string
    {
        return $text;
    }

    public static function blue(string $text): string
    {
        return $text;
    }
}
