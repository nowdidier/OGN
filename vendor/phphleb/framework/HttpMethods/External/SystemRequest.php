<?php


namespace Hleb\HttpMethods\External;

use Hleb\ParseException;

final class SystemRequest
{
    public function __construct(
        private readonly array       $cookieParams,
        private null|string          $rawBody,
        private null|array           $parsedBody,
        private readonly ?object     $streamBody,
        private readonly string      $method,
        private readonly array       $headers,
        private readonly string      $protocol,
        private readonly RequestUri  $uri,
    )
    {
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function getParsedBody(bool $cleared = true): null|array
    {
        if (!empty($_POST)) {
            return $cleared ? \hl_clear_tags($_POST) : $_POST;
        }
        if ($this->parsedBody === null) {
            $rawBody = $this->getRawBody();
            $body = \trim($rawBody);


            if ((str_starts_with($body, '{') && str_ends_with($body, '}')) ||
                (str_starts_with($body, '[') && str_ends_with($body, ']'))
            ) {
                try {
                    $this->parsedBody = \json_decode($body, true, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);
                } catch(\JsonException $e) {
                    throw new ParseException($e);
                }
            } else if (\str_contains($body, '=')) {
                \parse_str($body, $this->parsedBody);
            }
            (\is_array($this->parsedBody) || \is_object($this->parsedBody)) or $this->parsedBody = null;
        }
        if (\is_object($this->parsedBody)) {
            return $cleared ? \hl_clear_tags((array)$this->parsedBody) : (array)$this->parsedBody;
        }

        return $this->parsedBody;
    }

    public function getRawBody(): string
    {
        if ($this->rawBody !== null) {
            return $this->rawBody;
        }
        if ($this->streamBody !== null) {
            return (string)$this->streamBody;
        }
        return $this->rawBody = (string)\file_get_contents('php://input');
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): RequestUri
    {
        return $this->uri;
    }

    public function getGetParams(): array
    {
        return $_GET;
    }

    public function getGetParam(string|int|float $name): null|array|string|int|float
    {
        return $_GET[$name] ?? null;
    }

    public function getPostParams(): array
    {
        return $_POST;
    }

    public function getPostParam(string|int|float $name): null|array|string|int|float
    {
        return $_POST[$name] ?? null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return \array_key_exists(
            \strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),
            $this->headers
        );
    }

    public function getHeader($name): array
    {
        $name = \strtr($name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
        if (!$this->hasHeader($name)) {
            return [];
        }

        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return \implode(', ', $this->getHeader($name));
    }

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function getStreamBody(): ?object
    {
        return $this->streamBody;
    }
}
