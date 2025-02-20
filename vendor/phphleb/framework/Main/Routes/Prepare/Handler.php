<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Prepare;

use Hleb\Constructor\Data\DynamicParams;
use Hleb\AsyncRouteException;
use Hleb\RouteColoredException;
use Hleb\Main\Routes\StandardRoute;
use Hleb\Route\Fallback;

final class Handler
{
    private array $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $this->offset($rawData);
    }

    public function sort(): array
    {
        $this->isolateFallback();

        return $this->sortRoutes();
    }

    private function sortRoutes(): array
    {
        $result = [];
        $this->checkGroups();
        foreach ($this->rawData as $key => $data) {
            if ($data['method'] === StandardRoute::ADD_TYPE) {
                $result[$key] = $data;
                $routeGroups = $this->getGroupActions($key);
                $RouteReference = $this->getRouteReference($key);
                $result[$key]['actions'] = array_merge($routeGroups, $RouteReference);
            }
        }
        return $result;
    }

    private function isolateFallback(): void
    {
        $fallbacks = [];
        $search = false;

        foreach ($this->rawData as $key => $data) {
            $name = $data['name'] ?? null;
            $method = $data['method'] ?? null;

            if ($search) {
                if ($method === StandardRoute::CONTROLLER_TYPE ||
                    $method === StandardRoute::NAME_TYPE ||
                    $method === StandardRoute::MIDDLEWARE_TYPE
                ) {
                    $fallbacks[] = $data;
                    $this->rawData[$key] = null;
                    unset($this->rawData[$key]);
                } else {
                    $search = false;
                }
            }

            if ($method === StandardRoute::ADD_TYPE && $name === StandardRoute::FALLBACK_SUBTYPE) {
                $fallbacks[] = $data;
                $this->rawData[$key] = null;
                unset($this->rawData[$key]);
                $search = true;
            }
        }
        if ($fallbacks) {
            $this->rawData = \array_values($this->rawData);
            \array_unshift($this->rawData, ...\array_values($fallbacks));
            $this->rawData = $this->offset($this->rawData);
        }
    }

    private function getRouteReference(int $key): array
    {
        $result = [];
        foreach ($this->rawData as $num => $method) {
            if ($num <= $key) {
                continue;
            }
            if ($method['method'] === StandardRoute::ADD_TYPE ||
                $method['method'] === StandardRoute::TO_GROUP_TYPE ||
                $method['method'] === StandardRoute::END_GROUP_TYPE
            ) {
                break;
            }
            if (empty($method['from-group'])) {
                $result[] = $method;
            }
        }

        return $result;
    }

    private function checkGroups(): void
    {
        $groups = $this->rawData;
        $start = 0;
        $end = 0;
        foreach ($groups as $key => $item) {
            if ($item['method'] === StandardRoute::TO_GROUP_TYPE) {
                $start++;
                continue;
            }
            if ($item['method'] === StandardRoute::END_GROUP_TYPE) {
                $end++;
                continue;
            }
            unset($groups[$key]);
        }
        if (!$start && !$end) {
            return;
        }
        if ($start !== $end) {


            $this->error(AsyncRouteException::HL02_ERROR);
        }
        foreach ($groups as $key => $item) {
            if ($item['method'] === StandardRoute::TO_GROUP_TYPE) {
                $search = 0;
                foreach ($groups as $num => $group) {
                    if ($num < $key) {
                        continue;
                    }
                    if ($group['method'] === StandardRoute::TO_GROUP_TYPE) {
                        $search++;
                    }
                    if ($group['method'] === StandardRoute::END_GROUP_TYPE) {
                        $search--;
                    }
                    if ($search === 0) {
                        break;
                    }
                }
                if ($search !== 0) {


                    $this->error(AsyncRouteException::HL03_ERROR);
                }
            }
        }
    }

    public function getGroupActions(int $key): array
    {
        $items = $this->rawData;
        $groups = [];
        $num = 0;
        $result = [];

        foreach ($items as $k => $item) {
            if ($item['method'] === StandardRoute::TO_GROUP_TYPE) {
                $num++;
            }


            if ($item['method'] === StandardRoute::END_GROUP_TYPE) {
                unset($groups[$num]);
                $num--;
                continue;
            }
            if (!empty($item['from-group'])) {
                $groups[$num][] = $item;
            }


            if ($k === $key) {
                break;
            }
        }
        foreach ($groups as $numGroup => $group) {
            if ($num >= $numGroup) {
                $prepare = [$result, $group];
                $result = \array_merge(...$prepare);
            }
        }
        return $result;
    }

    private function offset(array $data): array
    {
        $result = [];
        if (\array_key_exists(0, $data)) {
            foreach($data as $key => $value) {
                $result[$key + 1] = $value;
            }
        }
        return $result;
    }

    private function error(string $tag): void
    {
        throw (new RouteColoredException($tag))->complete(DynamicParams::isDebug());
    }
}
