<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands;

use Hleb\Constructor\Actions\UpdateRouteCacheAction;
use Hleb\CoreException;

final class RouteCacheUpdater
{
    private int $code = 0;

    public function getCode(): int
    {
        return $this->code;
    }

    public function run(): string
    {
        try {
            try {
                (new UpdateRouteCacheAction())->run();
            } catch (CoreException) {
                $this->code = 1;
                return 'Error! Failed to save route cache. Check the necessary permissions.' . PHP_EOL;
            }
        } catch (\Throwable $e) {
            $this->code = 1;
            return $e->getMessage() . PHP_EOL;
        }

        if (\function_exists('opcache_reset')) {
            \opcache_reset();
        }
        $this->code = 0;
        return 'The route cache has been successfully updated!' . PHP_EOL;
    }
}
