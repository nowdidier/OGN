<?php


namespace Hleb\Main\Console\Extreme;

use JetBrains\PhpStorm\NoReturn;

final class ExtremeRequest
{

   public static function getUri(): string
   {
       $uri = \explode('?', $_SERVER['REQUEST_URI'] ?? '/');
       return \current($uri);
   }

   #[NoReturn] public static function redirect(string $uri): void
   {
       \async_exit('', 302, ['Location' => $uri]);
   }
}
