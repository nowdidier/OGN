<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\CommandDetails;

use Hleb\Helpers\TaskHelper;

final class CustomList
{

    public function get(): array
    {
        $custom = [];
        foreach ((new TaskHelper())->getCommands() as $command) {
            $custom[] = $command['name'];
        }
        return $custom;
    }
}
