<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands;

use Hleb\Constructor\Data\SystemSettings;
use Hleb\Helpers\DirectoryCleaner;

final class TwigCacheUpdater
{
    private int $code = 0;

    public function getCode(): int
    {
        return $this->code;
    }

    public function run(): string
    {
        $cleaner = new DirectoryCleaner();
        $cleaner->forceRemoveDir(SystemSettings::getRealPath('storage') . '/cache/twig/compilation');
        $errors = $cleaner->getErrors();
        if ($errors) {
            $this->code = 1;
            return \end($errors) . PHP_EOL;
        }

        return 'Successfully cleared the Twig templating cache.' . PHP_EOL;
    }
}
