<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Controller;

trait InsertControllerTrait
{

    public function controller(string $target, ?string $method = null): Controller
    {
        return new Controller($target, $method);
    }
}
