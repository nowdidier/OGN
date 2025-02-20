<?php

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class FileResourceModificationException extends CoreProcessException implements FileSystemException
{
}
