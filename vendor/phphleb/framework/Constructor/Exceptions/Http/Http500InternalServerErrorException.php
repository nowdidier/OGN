<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http500InternalServerErrorException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Internal Server Error')
    {
        $this->initException(500, $message);

        parent::__construct($message);
    }
}
