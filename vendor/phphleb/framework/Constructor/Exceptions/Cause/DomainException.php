<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class DomainException extends \DomainException implements CoreException
{
}
