<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Middleware;

trait InsertBeforeTrait
{

    public function before(string $target, ?string $method = null, array $data = []): Middleware
    {
        return new Middleware($target, $method, $data);
    }
}
