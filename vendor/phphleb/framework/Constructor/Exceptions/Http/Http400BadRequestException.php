<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http400BadRequestException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Bad Request')
    {
        $this->initException(400, $message);

        parent::__construct($message);
    }
}
