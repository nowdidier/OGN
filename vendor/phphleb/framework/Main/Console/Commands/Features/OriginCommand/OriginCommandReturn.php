<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\OriginCommand;

use Hleb\Main\Console\Commands\Features\FeatureInterface;

final class OriginCommandReturn implements FeatureInterface
{
    private const DESCRIPTION = 'RETURN_COMMAND_ARGUMENTS';

    #[\Override]
    public function run(array $argv): string
    {
        $argv and \array_shift($argv);
        if (!$argv) {
            return '';
        }
        return \implode(' ', $argv) . PHP_EOL;
    }

    #[\Override]
    public static function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    #[\Override]
    public function getCode(): int
    {
        return 0;
    }
}
