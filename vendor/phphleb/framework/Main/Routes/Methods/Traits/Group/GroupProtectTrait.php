<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits\Group;


use Hleb\Route\Group\GroupProtect;

trait GroupProtectTrait
{

    public function protect(string|array $rules = 'CSRF'): GroupProtect
    {
        return new GroupProtect($rules);
    }
}
