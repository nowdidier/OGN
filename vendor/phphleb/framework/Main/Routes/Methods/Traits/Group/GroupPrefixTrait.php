<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupPrefix;

trait GroupPrefixTrait
{

    public function prefix(string $prefix): GroupPrefix
    {
        return new GroupPrefix($prefix);

    }
}
