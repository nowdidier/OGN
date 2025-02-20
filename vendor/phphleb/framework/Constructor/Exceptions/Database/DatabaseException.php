<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class DatabaseException extends \PDOException implements CoreException
{
}
