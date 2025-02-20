<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use Random\Randomizer;

#[Accessible]
final class Abracadabra
{
    private const BASE_SYMBOLS = 'ZYXWVUTSRQPONMLKJIHGFEDCBAzyxwvutsrqponmlkjihgfedcba9876543210';

    private const SPECIAL_SYMBOLS = '-@#$_%&.~=';

    public static function generate(int $length = 64, bool $extended = false): string
    {
        $length < 5 and $length = 5;
        $symbols = $extended ? self::BASE_SYMBOLS . self::SPECIAL_SYMBOLS : self::BASE_SYMBOLS;

        if (PHP_VERSION_ID < 80300) {
            $key = '';
            $sampleLast = \strlen($symbols) - 1;
            for ($i = 0; $i < $length; $i++) {
                $key .= $symbols[\rand(0, $sampleLast)];
            }

            return $key;
        }

        return (new Randomizer())->getBytesFromString($symbols, $length);
    }

}
