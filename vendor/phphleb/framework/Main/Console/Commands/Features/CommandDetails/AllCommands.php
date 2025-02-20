<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\CommandDetails;

use Hleb\Main\Console\Commands\Features\FeatureInterface;

final class AllCommands implements FeatureInterface
{
    private const DESCRIPTION = 'Lists all supported commands for autocompletion.';

    public function __construct(private readonly array $commands)
    {
    }

    #[\Override]
    public function run(array $argv): string|false
    {
        try {
            $custom = (new CustomList())->get();
            $all = \array_merge($this->commands, $custom);
        } catch (\Throwable) {


            return "";
        }

        return \implode(PHP_EOL, $all);
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
