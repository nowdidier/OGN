<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Plain;

trait InsertPlainTrait
{

    public function plain(bool $on = true): Plain
    {
        return new Plain($on);
    }
}
