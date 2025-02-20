<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http410GoneException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Gone')
    {
        $this->initException(410, $message);

        parent::__construct($message);
    }
}
