<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class CoreProcessException extends \RuntimeException implements CoreException
{
}
