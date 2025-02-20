<?php


declare(strict_types=1);

namespace Hleb\Base;

use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Models\ModelTrait;

#[AvailableAsParent]
abstract class Model
{
    use ModelTrait;
}
