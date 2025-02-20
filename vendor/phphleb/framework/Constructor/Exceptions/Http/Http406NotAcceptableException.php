<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http406NotAcceptableException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Not Acceptable')
    {
        $this->initException(406, $message);

        parent::__construct($message);
    }
}
