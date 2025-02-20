<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\HttpMethods\External\RequestUri;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Reference\RequestInterface;

#[Accessible]
final class Request extends BaseSingleton
{
    private static RequestInterface|null $replace = null;

    public static function getMethod(): string
    {
        if (self::$replace) {
            return self::$replace->getMethod();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getMethod();
    }

    public static function isMethod(string $name): bool
    {
        if (self::$replace) {
            return self::$replace->isMethod($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->isMethod($name);
    }

    public static function get(string|int $name): DataType
    {
        if (self::$replace) {
            return self::$replace->get($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->get($name);
    }

    public static function allGet(bool $cleared = true): array
    {
        if (self::$replace) {
            return self::$replace->allGet($cleared);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->allGet($cleared);
    }

    public static function post(string|int $name): DataType
    {
        if (self::$replace) {
            return self::$replace->post($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->post($name);
    }

    public static function allPost(bool $cleared = true): array
    {
        if (self::$replace) {
            return self::$replace->allPost($cleared);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->allPost($cleared);
    }

    public static function input(): array
    {
        if (self::$replace) {
            return self::$replace->input();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->input();
    }

    public static function param(string $name): DataType
    {
        if (self::$replace) {
            return self::$replace->param($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->param($name);
    }

    public static function data(): array
    {
        if (self::$replace) {
            return self::$replace->data();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->data();
    }

    public static function rawData(): array
    {
        if (self::$replace) {
            return self::$replace->rawData();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->rawData();
    }

    public static function getParsedBody(): array
    {
        if (self::$replace) {
            return self::$replace->getParsedBody();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getParsedBody();
    }

    public static function getRawBody(): string
    {
        if (self::$replace) {
            return self::$replace->getRawBody();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getRawBody();
    }

    public static function getUri(): RequestUri
    {
        if (self::$replace) {
            return self::$replace->getUri();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getUri();
    }

    public static function isAjax(): bool
    {
        if (self::$replace) {
            return self::$replace->isAjax();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->isAjax();
    }

    public static function getFiles(string|int|null $name = null): null|array|object
    {
        if (self::$replace) {
            return self::$replace->getFiles($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getFiles($name);
    }

    public static function isHttpSecure(): bool
    {
        if (self::$replace) {
            return self::$replace->isHttpSecure();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->isHttpSecure();
    }

    public static function getHost(): string
    {
        if (self::$replace) {
            return self::$replace->getHost();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHost();
    }

    public static function getHostName(): string
    {
        if (self::$replace) {
            return self::$replace->getHostName();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHostName();
    }

    public static function getHttpScheme(): string
    {
        if (self::$replace) {
            return self::$replace->getHttpScheme();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHttpScheme();
    }

    public static function getSchemeAndHost(): string
    {
        if (self::$replace) {
            return self::$replace->getSchemeAndHost();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getSchemeAndHost();
    }

    public static function getAddress(): string
    {
        if (self::$replace) {
            return self::$replace->getAddress();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getAddress();
    }

    public static function getProtocolVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getProtocolVersion();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getProtocolVersion();
    }

    public static function getHeaders(): array
    {
        if (self::$replace) {
            return self::$replace->getHeaders();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHeaders();
    }

    public static function hasHeader($name): bool
    {
        if (self::$replace) {
            return self::$replace->hasHeader($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->hasHeader($name);
    }

    public static function getHeader($name): array
    {
        if (self::$replace) {
            return self::$replace->getHeader($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHeader($name);
    }

    public static function getSingleHeader($name): DataType
    {
        if (self::$replace) {
            return self::$replace->getSingleHeader($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getSingleHeader($name);
    }

    public static function server($name): mixed
    {
        if (self::$replace) {
            return self::$replace->server($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->server($name);
    }

    public static function isCurrent(string $uri): bool
    {
        if (self::$replace) {
            return self::$replace->isCurrent($uri);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->isCurrent($uri);
    }

    public static function getHeaderLine($name): string
    {
        if (self::$replace) {
            return self::$replace->getHeaderLine($name);
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getHeaderLine($name);
    }

    public static function getStreamBody(): ?object
    {
        if (self::$replace) {
            return self::$replace->getStreamBody();
        }

        return BaseContainer::instance()->get(RequestInterface::class)->getStreamBody();
    }

    #[ForTestOnly]
    public static function replaceWithMock(RequestInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }
}
