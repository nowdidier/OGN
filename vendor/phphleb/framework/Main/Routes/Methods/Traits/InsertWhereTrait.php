<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;


use Hleb\Route\Where;

trait InsertWhereTrait
{

    public function where(array $rules): Where
    {
        return new Where($rules);

    }
}
