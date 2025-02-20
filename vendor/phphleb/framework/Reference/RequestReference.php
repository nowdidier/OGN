<?php



namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Data\DynamicParams;
use Hleb\HttpMethods\External\RequestUri;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\ContainerUniqueItem;

#[Accessible] #[AvailableAsParent]
class RequestReference extends ContainerUniqueItem implements RequestInterface, Interface\Request, RollbackInterface
{
    private static array $cachedParams = [];

    #[\Override]
    public function getMethod(): string
    {
        return DynamicParams::getRequest()->getMethod();
    }

    #[\Override]
    public function isMethod(string $name): bool
    {
        return $this->getMethod() === \strtoupper($name);
    }

    #[\Override]
    public function get(string|int $name): ?DataType
    {
        if (\array_key_exists($name, self::$cachedParams['get'] ?? [])) {
            return self::$cachedParams['get'][$name];
        }

        return self::$cachedParams['get'][$name] = new DataType($_GET[$name] ?? null);
    }

    #[\Override]
    public function allGet(bool $cleared = true): array
    {
        return $cleared ? \hl_clear_tags($_GET ?? []) : $_GET ?? [];
    }

    #[\Override]
    public function allPost(bool $cleared = true): array
    {
        return $cleared ? \hl_clear_tags($_POST ?? []) : $_POST ?? [];
    }

    #[\Override]
    public function post(string|int $name): DataType
    {
        if (\array_key_exists($name, self::$cachedParams['post'] ?? [])) {
            return self::$cachedParams['post'][$name];
        }

        return self::$cachedParams['post'][$name] = new DataType($_POST[$name] ?? null);
    }

    #[\Override]
    public function param(string $name): DataType
    {
        if (\array_key_exists($name, self::$cachedParams['params'] ?? [])) {
            return self::$cachedParams['params'][$name];
        }
        $data = DynamicParams::getDynamicUriParams();
        return self::$cachedParams['params'][$name] = (\array_key_exists($name, $data) ? new DataType($data[$name]) : new DataType(null));
    }

    #[\Override]
    public function data(): array
    {
        $data = DynamicParams::getDynamicUriParams();
        if (!isset(self::$cachedParams['params']) || \count(self::$cachedParams['params']) !== \count($data)) {
            self::$cachedParams['params'] = [];
            foreach ($data as $name => $value) {
                self::$cachedParams['params'][$name] = new DataType($value);
            }
        }
        return self::$cachedParams['params'];
    }

    #[\Override]
    public function input(): array
    {
        return DynamicParams::getRequest()->getParsedBody(cleared: true) ?? [];
    }

    #[\Override]
    public function rawData(): array
    {
        return DynamicParams::getDynamicUriParams();
    }

    #[\Override]
    public function getParsedBody(): array
    {
        return DynamicParams::getRequest()->getParsedBody(cleared: false) ?? [];
    }

    #[\Override]
    public function getUri(): RequestUri
    {
        return DynamicParams::getRequest()->getUri();
    }

    #[\Override]
    public function getRawBody(): string
    {
        return DynamicParams::getRequest()->getRawBody();
    }

    #[\Override]
    public function isAjax(): bool
    {
        if (\strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
            return true;
        }
        if ($header = $this->getHeader('X-Requested-With')) {
            return \strtolower((string)\end($header)) === 'xmlhttprequest';
        }
        return false;
    }

    #[\Override]
    public function getFiles(string|int|null $name = null): null|array|object
    {
        return $name === null ? ($_FILES ?? []) : ($_FILES[$name] ?? null);
    }

    #[\Override]
    public function isHttpSecure(): bool
    {
        return DynamicParams::getRequest()->getUri()->getScheme() === 'https';
    }

    #[\Override]
    public function getHost(): string
    {
        return DynamicParams::getRequest()->getUri()->getHost();
    }

    #[\Override]
    public function getHostName(): string
    {
        $host = $this->getHost();

        return \strstr($host, ':', true) ?: $host;
    }

    #[\Override]
    public function getAddress(): string
    {
        $uri = DynamicParams::getRequest()->getUri();

        $path = $uri->getPath();

        return $uri->getScheme() . '://' . $uri->getHost() . ($path === '/' ? '' : $path);
    }

    #[\Override]
    public function getHttpScheme(): string
    {
        return 'http' . ($this->isHttpSecure() ? 's' : '') . '://';
    }

    #[\Override]
    public function getSchemeAndHost(): string
    {
        return self::getHttpScheme() . $this->getHost();
    }

    #[\Override]
    public function getProtocolVersion(): string
    {
        return DynamicParams::getRequest()->getProtocolVersion();
    }

    #[\Override]
    public function getHeaders(): array
    {
        return DynamicParams::getRequest()->getHeaders();
    }

    #[\Override]
    public function hasHeader($name): bool
    {
        return DynamicParams::getRequest()->hasHeader($name);
    }

    #[\Override]
    public function getHeader($name): array
    {
        return DynamicParams::getRequest()->getHeader($name);
    }

    #[\Override]
    public function getSingleHeader($name): DataType
    {
        $header = $this->getHeader($name);

        return new DataType($header ? \current($header) : null);
    }

    #[\Override]
    public function getHeaderLine($name): string
    {
        return DynamicParams::getRequest()->getHeaderLine($name);
    }

    #[\Override]
    public function server($name): mixed
    {
        return $_SERVER[$name] ?? null;
    }

    #[\Override]
    public function isCurrent(string $uri): bool
    {
        return \trim($uri, '/') === \trim($this->getUri()->getPath(), '/');
    }

    #[\Override]
    public function getStreamBody(): ?object
    {
        return DynamicParams::getRequest()->getStreamBody();
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$cachedParams = [];
    }
}
