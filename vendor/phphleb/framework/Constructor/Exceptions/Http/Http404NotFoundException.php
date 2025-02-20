<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http404NotFoundException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Not Found')
    {
        $this->initException(404, $message);

        parent::__construct($message);
    }
}
