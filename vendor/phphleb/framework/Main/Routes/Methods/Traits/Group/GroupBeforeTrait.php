<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupMiddleware;

trait GroupBeforeTrait
{

    public function before(string $target, ?string $method = null, array $data = []):  GroupMiddleware
    {
        return new GroupMiddleware($target, $method, $data);
    }
}
