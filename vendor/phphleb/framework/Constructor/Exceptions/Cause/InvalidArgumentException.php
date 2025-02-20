<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class InvalidArgumentException extends \InvalidArgumentException implements CoreException
{
}
