<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\CommandDetails;

use Hleb\Main\Console\Commands\Features\FeatureInterface;

final class CommandArgument implements FeatureInterface
{
    private const DESCRIPTION = 'List of arguments selected for the next possible argument.';

    public function __construct(private readonly array $commands)
    {
    }

    #[\Override]
    public function run(array $argv): string|false
    {
        $arguments = ['--help'];
        try {
            $custom = (new CustomList())->get();
            $all = \array_merge($this->commands, $custom);
            if (!\in_array(\trim(\current($argv)), $all, true)) {


                return '';
            }
            if (\in_array(\trim(\current($argv)), $custom, true)) {
                $arguments[] = "--desc";
            }

        } catch (\Throwable) {


            return "";
        }

        return \implode(PHP_EOL, $arguments);
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
