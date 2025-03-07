<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class StringHelper
{

    public static function compare(string $first, string $second): array
    {
        if (!$first && !$second) {
            return [];
        }
        $result = [];
        $f = \str_split($first);
        $s = \str_split($second);

        foreach ($f as $k => $v) {
            if (!\array_key_exists($k, $s)) {
                $result[] = ['first' => $v, 'pos' => $k, 'error' => 'not in second', 'err' => 1];
            } else if ($v !== $s[$k]) {
                $result[] = ['first' => $v, 'second' => $s[$k], 'pos' => $k, 'error' => 'does not match', 'err' => 3];
            }
        }
        for ($i = \count($f); $i < \count($s); $i++) {
            $result[] = ['second' => $s[$i], 'pos' => $i, 'error' => 'not in first', 'err' => 2];
        }
        return $result;
    }
}
