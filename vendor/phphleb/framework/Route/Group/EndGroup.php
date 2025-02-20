<?php


namespace Hleb\Route\Group;

use Hleb\Main\Routes\StandardRoute;

final class EndGroup extends StandardRoute
{
    public function __construct()
    {
        $this->register([
            'method' => self::END_GROUP_TYPE,
        ]);
    }
}
