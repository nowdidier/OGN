<?php

declare(strict_types=1);

namespace Hleb;

use Hleb\Constructor\Attributes\NotFinal;
use Hleb\Main\BaseErrorPage;
use RuntimeException;

#[NotFinal]
abstract class HttpException extends RuntimeException implements CoreException
{

    protected int $httpCode;

    protected string $messageContent;

    public function getHttpStatus(): int
    {
        return $this->httpCode;
    }

    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    protected function initException(int $httpCode, string $message): void
    {
        $this->httpCode = $httpCode;

        $this->messageContent = (new BaseErrorPage($httpCode, $message))->insert();
    }
}
