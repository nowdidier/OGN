<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupAfter;

trait GroupAfterTrait
{

    public function after(string $target, ?string $method = null, array $data = []):  GroupAfter
    {
        return new GroupAfter($target, $method, $data);
    }
}
