<?php


namespace Hleb\HttpMethods\External;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class Response
{
    final public const PHRASES = [
        100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing',
        200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-status', 208 => 'Already Reported',
        300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => 'Switch Proxy', 307 => 'Temporary Redirect',
        400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Time-out', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Large', 415 => 'Unsupported Media Type', 416 => 'Requested range not satisfiable', 417 => 'Expectation Failed', 418 => 'I\'m a teapot', 422 => 'Unprocessable Entity', 423 => 'Locked', 424 => 'Failed Dependency', 425 => 'Unordered Collection', 426 => 'Upgrade Required', 428 => 'Precondition Required', 429 => 'Too Many Requests', 431 => 'Request Header Fields Too Large', 451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Time-out', 505 => 'HTTP Version not supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage', 508 => 'Loop Detected', 511 => 'Network Authentication Required',
    ];

    private const UPPERCASE = ['MD5', 'MIME', 'TE', 'URI', 'WWW'];

    private array $body = [];

    private string $version = '1.1';

    private array $headers = [];

    public function __construct(
        \Stringable|string|null $body = null,
        private ?int $status = null,
        array $headers = [],
        private ?string $reason = null,
        ?string $version = null,
    ) {
        if ($body !== null) {
            $this->body[] = (string)$body;
        }
        if ($version === null) {
            if (!empty($_SERVER['SERVER_PROTOCOL'])) {
                $this->version = \trim(\strstr((string)$_SERVER['SERVER_PROTOCOL'], '/'), '/ ');
            }
        } else {
            $this->version = $version;
        }
        $this->addHeaders($headers);
        if ($status === null) {
            $this->status = 200;
            $this->reason = null;
        } else {
            $this->setStatus($status, $this->reason);
        }

    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status, ?string $reason = null): void
    {
        $this->status = $status;
        if ($reason === null && isset(self::PHRASES[$status])) {
            $this->reason = self::PHRASES[$status];
        } else {
            $this->reason = $reason ?? '';
        }
    }

    public function getReason(): ?string
    {
        return $this->reason ?: null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPrepareHeaders(): array
    {
        $result = [];
        foreach ($this->headers as $name => $headers) {
            $result[] = $name . ': ' . $headers;
       }
        return $result;
    }

    public function replaceHeaders(array $headers): void
    {
        $items = [];
        foreach ($headers as $name => $value) {
            if (\is_array($value)) {
                $items[$this->normalizeHeaderName($name)] = $value;
            } else {
               $items[$this->normalizeHeaderName($name)] = [$value];
            }
        }
        $this->headers = $items;
    }

    public function setHeader(string $name, int|float|string $value, bool $replace = true): void
    {
        $name = $this->normalizeHeaderName($name);
        if ($replace) {
            $this->headers[$name] = [$value];
            return;
        }
        $this->headers[$name][] = $value;
        $this->headers[$name] = \array_unique($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        $name = $this->normalizeHeaderName($name);
        if (\array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return [];
    }

    public function hasHeader(string $name): bool
    {
        return (bool)$this->getHeader($name);
    }

    public function addHeaders(array $headers, bool $replace = true): void
    {
        foreach($headers as $k => $val) {


            if (\is_numeric($k)) {
                $list = \explode(':', $val);
                $name = \array_shift($list);
                $headers[$name][] = \trim(\implode(':', $list));
                $headers[$name] = \array_unique($headers[$name]);
            }
        }
        foreach ($headers as $key => $value) {
            if (!\is_array($value)) {
                $value = [$value];
            }
            $name = $this->normalizeHeaderName($key);
            if (isset($this->headers[$name])) {
                $this->headers[$name] = \array_unique($replace ? $value : \array_merge($this->headers[$name], $value));
            } else {
                $this->headers[$name] = \array_unique($value);
            }
        }
    }

    public function getBody(): string
    {
        return \implode($this->body);
    }

    public function setBody($body): void
    {
        $this->body = [(string)$body];
    }

    public function addToBody(mixed $content): void
    {
        $this->body[] = (string)$content;
    }

    public function removeFromBody(): mixed
    {
        return \array_pop($this->body);
    }

    public function clearBody(): void
    {
        $this->body = [];
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getArgs(): array
    {
        return [
            $this->getStatus(),
            $this->getHeaders(),
            $this->getBody(),
            $this->getVersion(),
            $this->getReason(),
        ];
    }

    private function normalizeHeaderName(string $name): string
    {
        $name = \trim($name);
        $name = \str_replace('_', '-', \strtoupper($name));
        $parts = \explode('-', $name);
        foreach ($parts as &$part) {
            if (\in_array($part, self::UPPERCASE)) {
                continue;
            }
            $part = \ucwords(\strtolower($part));
        }

        return \implode('-', $parts);
    }
}
