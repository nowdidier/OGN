<?php


namespace Hleb\HttpMethods\External;


final readonly class RequestUri
{
    public function __construct(
        private string $host,
        private string $path,
        private string $query,
        private int|null $port,
        private string $scheme,
        private string $ip,
    )
    {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getPort(): int|null
    {
        return $this->port ?: null;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function __toString(): string
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->getPath() . $this->getQuery();
    }
}
