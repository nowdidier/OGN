<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\ExecutionSpeed;

use Hleb\Main\Console\Commands\Features\FeatureInterface;

final class ExecutionSpeed implements FeatureInterface
{
    private const DESCRIPTION = 'Returns the execution time (sec) of an empty console request by the framework.';

    #[\Override]
    public function run(array $argv): string
    {
        return (string)(\microtime(true) - HLEB_START);
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
