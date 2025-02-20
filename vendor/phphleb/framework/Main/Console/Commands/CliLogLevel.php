<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands;

use Hleb\Constructor\Data\MainLogLevel;

final class CliLogLevel
{

    public function run(?string $level): string
    {
        if ($level === null) {
            return MainLogLevel::get() . PHP_EOL;
        }

        if ($level === 'default') {
            return MainLogLevel::setDefault() . PHP_EOL;
        }

        return MainLogLevel::set($level) . PHP_EOL;
    }
}
