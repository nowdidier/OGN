<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupMiddleware;

trait GroupMiddlewareTrait
{

    public function middleware(string $target, ?string $method = null, array $data = []):  GroupMiddleware
    {
        return new GroupMiddleware($target, $method, $data);
    }
}
