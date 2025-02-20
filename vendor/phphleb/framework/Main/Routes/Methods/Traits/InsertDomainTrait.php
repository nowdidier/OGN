<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Domain;

trait InsertDomainTrait
{

    public function domain(string|array $name, int $level = 2): Domain
    {
        return new Domain($name, $level);
    }
}
