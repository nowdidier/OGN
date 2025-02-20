<?php


namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Http401UnauthorizedException extends HttpException
{
    #[NotFinal]
    public function __construct(string $message = 'Unauthorized')
    {
        $this->initException(401, $message);

        parent::__construct($message);
    }
}
