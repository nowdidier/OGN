<?php



namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\HttpMethods\External\Response as SystemResponse;
use Hleb\Main\Insert\ContainerUniqueItem;

#[Accessible] #[AvailableAsParent]
class ResponseReference extends ContainerUniqueItem implements ResponseInterface, Interface\Response, RollbackInterface
{

    private static SystemResponse|null $response = null;

    #[\Override]
    public function getInstance(): ?SystemResponse
    {
        return self::$response;
    }

    #[\Override]
    public function getStatus(): int
    {
        return self::$response->getStatus();
    }

    #[\Override]
    public function setStatus(int $status, ?string $reason = null): void
    {
        self::$response->setStatus($status, $reason);
    }

    #[\Override]
    public function getHeaders(): array
    {
        return self::$response->getHeaders();
    }

    #[\Override]
    public function replaceHeaders(array $headers): void
    {
        self::$response->replaceHeaders($headers);
    }

    #[\Override]
    public function addHeaders(array $headers, bool $replace = true): void
    {
        self::$response->addHeaders($headers, $replace);
    }

    #[\Override]
    public function setHeader(string $name, float|int|string $value, bool $replace = true): void
    {
        self::$response->setHeader($name, $value, $replace);
    }

    #[\Override]
    public function hasHeader(string $name): bool
    {
        return self::$response->hasHeader($name);
    }

    #[\Override]
    public function getHeader(string $name): array
    {
        return self::$response->getHeader($name);
    }

    #[\Override]
    public function get(): string
    {
        return $this->getBody();
    }

    #[\Override]
    public function set(string|\Stringable $body, ?int $status = null): void
    {
        if ($status !== null) {
            $this->setStatus($status);
        }
        $this->setBody($body);
    }

    #[\Override]
    public function add(mixed $content): void
    {
        $this->addToBody($content);
    }

    #[\Override]
    public function getBody(): string
    {
        return self::$response->getBody();
    }

    #[\Override]
    public function setBody($body): void
    {
        self::$response->setBody($body);
    }

    #[\Override]
    public function addToBody(mixed $content): void
    {
        self::$response->addToBody($content);
    }

    #[\Override]
    public function clearBody(): void
    {
        self::$response->clearBody();
    }

    #[\Override]
    public function removeFromBody(): mixed
    {
        return self::$response->removeFromBody();
    }

    #[\Override]
    public function getVersion(): string
    {
        return self::$response->getVersion();
    }

    #[\Override]
    public function setVersion(string $version): void
    {
        self::$response->setVersion($version);
    }

    #[\Override]
    public function getReason(): ?string
    {
        return self::$response->getReason();
    }

    #[\Override]
    public static function init(SystemResponse $response): void
    {
        self::$response = $response;
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$response = new SystemResponse();
    }
}
