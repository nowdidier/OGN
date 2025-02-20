<?php


namespace Hleb\Constructor\Actions;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Cache\CacheRoutes;
use Hleb\CoreProcessException;
use Hleb\RouteColoredException;
use Hleb\Main\Routes\Update\RouteData;

#[Accessible]
final class UpdateRouteCacheAction implements ActionInterface
{

    #[\Override]
    public function run(): void
    {
        try {
            (new CacheRoutes((new RouteData())->dataExtraction()))->save();
        } catch (RouteColoredException $e) {
            throw new CoreProcessException($e->getError());
        }
    }
}
