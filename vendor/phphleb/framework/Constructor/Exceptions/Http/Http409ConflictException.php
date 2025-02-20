<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http409ConflictException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Conflict')
    {
        $this->initException(409, $message);

        parent::__construct($message);
    }
}
