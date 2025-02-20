<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Prepare;

class Defender
{

    public function handle(array &$data): void
    {
        \array_walk_recursive($data, static function (&$value) {
            if (\is_string($value)) {
                if (\str_contains($value, "'") || \str_ends_with($value, '\\')) {
                    $value = \addcslashes($value, "'\\");
                }
            }
        });
    }
}
