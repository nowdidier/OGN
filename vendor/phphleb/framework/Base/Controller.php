<?php


declare(strict_types=1);

namespace Hleb\Base;

use Hleb\Constructor\Attributes\AvailableAsParent;

#[AvailableAsParent]
abstract class Controller extends Container
{
    public function __construct(#[\SensitiveParameter] array $config = [])
    {


        parent::__construct($config);
    }
}
