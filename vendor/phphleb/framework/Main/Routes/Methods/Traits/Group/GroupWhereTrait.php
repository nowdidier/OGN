<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;

use Hleb\Route\Group\GroupWhere;

trait GroupWhereTrait
{

    public function where(array $rules): GroupWhere
    {
        return new GroupWhere($rules);
    }
}
