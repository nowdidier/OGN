<?php


namespace Hleb\Main\Console;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\FileResourceModificationException;
use Hleb\Main\Routes\Search\FindRoute;

#[Accessible]
final class ConsoleAdapter
{

   public function updateRouteCache(): void
   {
       (new ConsoleHandler())->updateRouteCache();
   }

   public function updateTwigCache(): bool
   {
       if (!\class_exists('Twig\Environment')) {
           return false;
       }
       (new ConsoleHandler())->updateTwigCache();

       return true;
   }

   public function searchRoute(string $url, $method = 'GET', ?string $domain = null): bool
   {
       $handler = (new FindRoute($url));
       $search = (bool)$handler->one($method, $domain);
       if ($handler->isBlocked() || $handler->getError()) {
           return false;
       }

       return $search;
   }

   public function lockProject(bool $lockStatus): void
   {
       $file = SystemSettings::getRealPath('@storage/cache/routes/lock-status.info');
       \hl_create_directory($file);
       \file_put_contents($file, (int)$lockStatus);
       @\chmod($file, 0664);
   }
}
