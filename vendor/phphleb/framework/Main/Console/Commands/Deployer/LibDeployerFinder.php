<?php


namespace Hleb\Main\Console\Commands\Deployer;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Data\SystemSettings;

#[Accessible]
final class LibDeployerFinder
{

    public function isExists(string $command): bool
    {
        return (bool)SystemSettings::getRealPath("@vendor/$command/updater.json");
    }
}
