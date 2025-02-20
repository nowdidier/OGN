<?php


declare(strict_types=1);

namespace Hleb\Base;

use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Containers\ContainerTrait;

#[AvailableAsParent]
abstract class Container
{
    use ContainerTrait;
}
