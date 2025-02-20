<?php


use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class AsyncExitException extends \ErrorException
{
    private int $status = 200;

    private array $headers = [];

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
