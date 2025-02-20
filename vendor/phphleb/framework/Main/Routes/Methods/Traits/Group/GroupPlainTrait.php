<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupPlain;

trait GroupPlainTrait
{

    public function plain(bool $on = true): GroupPlain
    {
        return new GroupPlain($on);
    }
}
