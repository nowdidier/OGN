<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;

use Hleb\Route\Page;

trait InsertPageTrait
{

    public function page(string $type, string $target, ?string $method = null): Page
    {
        return new Page($type, $target, $method);
    }
}
