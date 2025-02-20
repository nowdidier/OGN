<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands;

use Hleb\HlebBootstrap;
use Hleb\Main\Routes\Search\FindRoute;

final class SearchRoute
{
    use FindRouteTrait;

    private int $code = 0;

    public function getCode(): int
    {
        return $this->code;
    }

   public function run(null|string $url, null|string $httpMethod, null|string $domain): string
   {
       if ($url === null) {
           return 'Error! Required argument `url` not specified: php console --find-route <url> [method] [domain]' . PHP_EOL;
       }

       [$url, $domain] = $this->splitUrl($url, $domain);

       $block = $this->getBlock($url, $httpMethod, $domain);
       if (\is_string($block)) {
           $this->code = 1;
           return $block;
       }

       return ($block ? 'OK' : 'Not found.') . PHP_EOL;
   }
}
