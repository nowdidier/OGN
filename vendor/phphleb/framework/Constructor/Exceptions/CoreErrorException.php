<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class CoreErrorException extends \Exception implements CoreException
{
}
