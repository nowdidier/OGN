<?php


namespace Hleb\Constructor\Actions;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\CoreProcessException;
use Hleb\Reference\CacheReference;

#[Accessible]
final class ClearCacheAction implements ActionInterface
{

    #[\Override]
   public function run(): void
   {
       if (!(new CacheReference())->clear()) {
           throw new CoreProcessException('Failed to clear cache.');
       }
   }
}
