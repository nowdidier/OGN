<?php

declare(strict_types=1);

namespace Hleb\Constructor\Attributes\Autowiring;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class DI
{

    public function __construct(public string|object|null $classNameOrObject)
    {
    }
}
