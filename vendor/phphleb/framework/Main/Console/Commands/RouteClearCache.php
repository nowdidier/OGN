<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands;

use Hleb\Helpers\DirectoryCleaner;
use Hleb\Static\Path;

final class RouteClearCache
{
    private int $code = 0;

    public function getCode(): int
    {
        return $this->code;
    }

    public function run(): string
    {
        try {
               $cleaner = new DirectoryCleaner();
               $cleaner->forceRemoveDir(Path::get('@storage/cache/routes'));
               if ($cleaner->getErrors()) {
                   $this->code = 1;
                   return 'ERROR:' . implode(PHP_EOL, $cleaner->getErrors()) . PHP_EOL;
               }
        } catch (\Throwable $e) {
            $this->code = 1;
            return $e->getMessage() . PHP_EOL;
        }

        if (\function_exists('opcache_reset')) {
            \opcache_reset();
        }
        $this->code = 0;
        return 'The route cache has been successfully cleared!' . PHP_EOL;
    }
}
