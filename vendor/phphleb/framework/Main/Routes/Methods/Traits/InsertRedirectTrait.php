<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Methods\Traits;

use Hleb\Route\Redirect;

trait InsertRedirectTrait
{

    public function redirect(string $location, int $status = 302): Redirect
    {
        return new Redirect($location, $status);
    }
}
