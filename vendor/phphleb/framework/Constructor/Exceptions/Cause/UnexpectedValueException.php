<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class UnexpectedValueException extends \UnexpectedValueException implements CoreException
{
}
