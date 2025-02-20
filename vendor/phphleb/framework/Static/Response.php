<?php


namespace Hleb\Static;

use App\Bootstrap\BaseContainer;
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\ForTestOnly;
use Hleb\CoreProcessException;
use Hleb\HttpMethods\External\Response as SystemResponse;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Reference\ResponseInterface;

#[Accessible]
final class Response extends BaseAsyncSingleton implements RollbackInterface
{
    private static ResponseInterface|null $replace = null;

    public static function getInstance(): ?SystemResponse
    {
        if (self::$replace) {
            return self::$replace->getInstance();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getInstance();
    }

    public static function getStatus(): int
    {
        if (self::$replace) {
            return self::$replace->getStatus();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getStatus();
    }

    public static function setStatus(int $status, ?string $reason = null): void
    {
        if (self::$replace) {
            self::$replace->setStatus($status, $reason);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->setStatus($status, $reason);
        }
    }

    public static function getHeaders(): array
    {
        if (self::$replace) {
            return self::$replace->getHeaders();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getHeaders();
    }

    public static function replaceHeaders(array $headers): void
    {
        if (self::$replace) {
            self::$replace->replaceHeaders($headers);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->replaceHeaders($headers);
        }
    }

    public static function setHeader(string $name, int|float|string $value, bool $replace = true): void
    {
        if (self::$replace) {
            self::$replace->setHeader($name, $value, $replace);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->setHeader($name, $value, $replace);
        }
    }

    public static function hasHeader(string $name): bool
    {
        if (self::$replace) {
            return self::$replace->hasHeader($name);
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->hasHeader($name);
    }

    public static function getHeader(string $name): array
    {
        if (self::$replace) {
            return self::$replace->getHeader($name);
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getHeader($name);
    }

    public static function addHeaders(array $headers, bool $replace = true): void
    {
        if (self::$replace) {
            self::$replace->addHeaders($headers, $replace);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->addHeaders($headers, $replace);
        }
    }

    public static function get(): string
    {
        if (self::$replace) {
            return self::$replace->get();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->get();
    }

    public static function set(string|\Stringable $body, ?int $status = null): void
    {
        if (self::$replace) {
            self::$replace->set($body, $status);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->set($body, $status);
        }
    }

    public static function add(mixed $content): void
    {
        if (self::$replace) {
            self::$replace->add($content);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->add($content);
        }
    }

    public static function getBody(): string
    {
        if (self::$replace) {
            return self::$replace->getBody();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getBody();
    }

    public static function setBody($body): void
    {
        if (self::$replace) {
            self::$replace->setBody($body);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->setBody($body);
        }
    }

    public static function addToBody(mixed $content): void
    {
        if (self::$replace) {
            self::$replace->addToBody($content);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->addToBody($content);
        }
    }

    public static function clearBody(): void
    {
        if (self::$replace) {
            self::$replace->clearBody();
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->clearBody();
        }
    }

    public static function removeFromBody(): mixed
    {
        if (self::$replace) {
            return self::$replace->removeFromBody();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->removeFromBody();
    }

    public static function getVersion(): string
    {
        if (self::$replace) {
            return self::$replace->getVersion();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getVersion();
    }

    public function setVersion(string $version): void
    {
        if (self::$replace) {
            self::$replace->setVersion($version);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)->setVersion($version);
        }
    }

    public static function getReason(): ?string
    {
        if (self::$replace) {
            return self::$replace->getReason();
        }

        return BaseContainer::instance()->get(ResponseInterface::class)->getReason();
    }

    public static function init(SystemResponse $response): void
    {
        if (self::$replace) {
            self::$replace::init($response);
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)::init($response);
        }
    }

    #[\Override]
    public static function rollback(): void
    {
        if (self::$replace) {
            self::$replace::rollback();
        } else {
            BaseContainer::instance()->get(ResponseInterface::class)::rollback();
        }
    }

    #[ForTestOnly]
    public static function replaceWithMock(ResponseInterface|null $mock): void
    {
        if (\defined('HLEB_CONTAINER_MOCK_ON') && !HLEB_CONTAINER_MOCK_ON) {
            throw new CoreProcessException('The action is prohibited in the settings.');
        }
        self::$replace = $mock;
    }

}
