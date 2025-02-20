<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\After;

trait InsertAfterTrait
{

    public function after(string $target, ?string $method = null, array $data = []): After
    {
        return new After($target, $method, $data);
    }
}
