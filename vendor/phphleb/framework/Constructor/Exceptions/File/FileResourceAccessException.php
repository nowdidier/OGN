<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class FileResourceAccessException extends CoreProcessException implements FileSystemException
{
}
