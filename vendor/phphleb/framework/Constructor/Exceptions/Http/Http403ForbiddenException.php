<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http403ForbiddenException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Forbidden')
    {
        $this->initException(403, $message);

        parent::__construct($message);
    }
}
