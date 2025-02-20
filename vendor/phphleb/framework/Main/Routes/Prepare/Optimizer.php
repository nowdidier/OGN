<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Prepare;

use Hleb\Main\Routes\StandardRoute;

final class Optimizer
{
    private array $routesByMethod = [];

    private array $routesInfo = [];

    private array $routesList = [];

    public function __construct(readonly private array $routesData)
    {
    }

    public function update(): self
    {
        $this->routesDataByMethod();

        return $this;
    }

    public function getRoutesByMethod(): array
    {
        return $this->routesByMethod;
    }

    public function getRoutesInfo(): array
    {
        return $this->routesInfo;
    }

    public function getRoutesList(): array
    {
        return $this->routesList;
    }

    private function routesDataByMethod(): void
    {
        $this->routesInfo = [
            'index_page' => 0,
            'index_page_name' => '',
            'has_dynamic_rules' => 0,
            'has_where' => 0,
            'has_protect' => 0,
            'no_session' => 0,
            'all_methods' => [],
            'has_modules' => 0,
            'has_pages' => 0,
            'has_plain' => 0,
        ];
        $this->routesList = [];
        $this->routesByMethod = [];

        foreach ($this->routesData as $key => $route) {
            $address = $this->createAddress($route);
            $route['full-address'] = $address;

            if (\str_contains($address, '?') || \str_contains($address, '{')) {
                $this->routesInfo['has_dynamic_rules'] = 1;
            }

            $httpMethods = $route['types'];
            $isIndexPage = \in_array('GET', $httpMethods, true) && $route['data']['route'] === '/' && $this->routesInfo['index_page'] === 0;
            if ($isIndexPage) {
                $this->routesInfo['index_page'] = $key;
            }
            foreach ($httpMethods as $method) {
                $method = \strtolower($method);
                $this->routesByMethod[$method][$key] = $route;
                if (\in_array($method, $this->routesInfo['has_methods'] ?? [], true)) {
                    $this->routesInfo['has_methods'][] = $method;
                }
                $this->routesList[$method][] = $this->createRouteRequest($address, $route, $key);
            }
            foreach ($route['actions'] ?? [] as $action) {
                if ($action['method'] === StandardRoute::PROTECT_TYPE) {
                    $this->routesInfo['has_protect'] = 1;
                }
                if ($action['method'] === StandardRoute::NO_DEBUG_TYPE) {
                    $this->routesInfo['no_debug'] = 1;
                }
                if ($action['method'] === StandardRoute::PLAIN_TYPE) {
                    $this->routesInfo['has_plain'] = 1;
                }
                if ($action['method'] === StandardRoute::WHERE_TYPE) {
                    $this->routesInfo['has_where'] = 1;
                }
                if ($action['method'] === StandardRoute::MODULE_TYPE) {
                    $this->routesInfo['has_modules'] = 1;
                }
                if ($action['method'] === StandardRoute::PAGE_TYPE) {
                    $this->routesInfo['has_pages'] = 1;
                }
                if ($action['method'] === StandardRoute::DOMAIN_TYPE && $route['data']['route'] === '/') {
                    $this->routesInfo['index_page'] = 0;
                }
                if ($action['method'] === StandardRoute::NAME_TYPE && $isIndexPage) {
                    $this->routesInfo['index_page_name'] = $action['name'];
                }
            }
        }
    }

    private function createAddress(array $route): string
    {
        $list = [];
        $base = \trim(\preg_replace('|([/]+)|s', '/', $route['data']['route']), '/');
        foreach ($route['actions'] ?? [] as $action) {
            if ($action['method'] === StandardRoute::PREFIX_TYPE) {
                $list[] = \trim(\preg_replace('|([/]+)|s', '/', $action['prefix']), '/');
            }
        }

        return $this->withIndex(\implode('/', \array_merge($list, [$base])));
    }

    private function createRouteRequest(string $address, array $route, int $key): array
    {
        $address !== '/' and $address = \rtrim($address, '/');
        $result = ['a' => $address, 'k' => $key];
        $domain = [];
        $result['f'] = \str_contains($address, '/') ? $this->withIndex(\strstr($address, '/', true)) : $address;

        if (!empty($route['actions'])) {
            foreach ($route['actions'] as $action) {
                if ($action['method'] === StandardRoute::WHERE_TYPE) {
                    $result['w'] = \array_merge($result['w'] ?? [], $action['data']['rules'] ?? []);
                }
                if ($action['method'] === StandardRoute::NAME_TYPE) {
                    $result['i'] = $action['name'];
                }
                if ($action['method'] === StandardRoute::DOMAIN_TYPE) {
                    $domain[$action['level']] = \array_merge($domain[$action['level']] ?? [], $action['name']);
                }
                if ($action['method'] === StandardRoute::PROTECT_TYPE) {


                    if (!empty($action['data']['rules'])) {
                        $result['p'] = $action['data']['rules'];
                    }
                }
                if ($action['method'] === StandardRoute::NO_DEBUG_TYPE) {
                    $result['u'] = 1;
                }
                if ($action['method'] === StandardRoute::PLAIN_TYPE) {


                    if (isset($action['data']['on'])) {
                        $result['b'] = (int)$action['data']['on'];
                    }
                }
            }
        }
        if (\str_contains($address, '{')) {
            $result['d'] = 1;
        }
        if (\str_contains($address, '?')) {
            $result['v'] = 1;
        }
        if (\str_contains($address, '...')) {
            $result['m'] = 1;
        }
        if ($result['f'] === $address) {
            $result['s'] = 1;
        }
        if (\str_contains($result['f'], '{') || \str_contains($result['f'], '?')) {
            $result['n'] = 1;
        }
        if ($route['name'] === StandardRoute::FALLBACK_SUBTYPE) {
            $result['c'] = 1;
        }
        if ($domain) {
            $result['h'] = $domain;
        }

        return $result;
    }

    private function withIndex(string $address): string
    {
        return $address !== '' ? $address : '/';
    }

}
