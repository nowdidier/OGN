<?php

declare(strict_types=1);

namespace Hleb\Constructor\Containers;

use App\Bootstrap\BaseContainer;
use App\Bootstrap\ContainerInterface;
use Hleb\Reference\CookieInterface;
use Hleb\Reference\RequestInterface;
use Hleb\Reference\ResponseInterface;
use Hleb\Reference\RouterInterface;
use Hleb\Reference\SettingInterface;

trait ContainerTrait
{

    protected readonly array $config;

    protected readonly ContainerInterface $container;

    public function __construct(#[\SensitiveParameter] array $config = [])
    {
        $this->config = $config;

        $this->container = $config['container'] ?? BaseContainer::instance();
    }

    final protected function cookies(): CookieInterface
    {
        return $this->container->get(CookieInterface::class);
    }

    final protected function request(): RequestInterface
    {
        return $this->container->get(RequestInterface::class);
    }

    final protected function response(): ResponseInterface
    {
        return $this->container->get(ResponseInterface::class);
    }

    final protected function settings(): SettingInterface
    {
        return $this->container->get(SettingInterface::class);
    }

    final protected function router(): RouterInterface
    {
        return $this->container->get(RouterInterface::class);
    }
}
