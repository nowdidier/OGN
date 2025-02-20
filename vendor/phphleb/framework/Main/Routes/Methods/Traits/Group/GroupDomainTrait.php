<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupDomain;

trait GroupDomainTrait
{

    public function domain(string|array $name, int $level = 2): GroupDomain
    {
        return new GroupDomain($name, $level);
    }
}
