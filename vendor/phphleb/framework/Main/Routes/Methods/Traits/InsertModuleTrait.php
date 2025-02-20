<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Module;

trait InsertModuleTrait
{

    public function module(string $name, string $target, ?string $method = null): Module
    {
        return new Module($name, $target, $method);
    }
}
