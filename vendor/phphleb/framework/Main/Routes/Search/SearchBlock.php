<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Search;

use Hleb\Helpers\RangeChecker;
use Hleb\HttpMethods\External\SystemRequest;

final class SearchBlock
{
    private array $data = [];

    private int $fallback = 0;

    private array $protected = [];

    private ?string $routeName = null;

    private ?bool $isPlain = null;

    private ?bool $isNoDebug = null;

    private bool $isCompleteAddress = true;

    public function __construct(
        readonly private SystemRequest $request,
        readonly private array         $list
    )
    {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function getFallback(): int
    {
        return $this->fallback;
    }

    public function protected(): array
    {
        return $this->protected;
    }

    public function getIsPlain(): null|bool
    {
        return $this->isPlain;
    }

    public function getIsNoDebug(): null|bool
    {
        return $this->isNoDebug;
    }

    public function getIsCompleteAddress(): bool
    {
        return $this->isCompleteAddress;
    }

    public function getNumber(): int|false
    {
        $this->data = [];
        $this->protected = [];
        $address = $this->withIndex(\trim($this->request->getUri()->getPath(), '/'));
        $firstPart = \str_contains($address, '/') ? $this->withIndex(strstr($address, '/', true)) : $address;
        $this->fallback = 0;
        $fallbackNumber = 0;
        $addressParts = [];

        foreach ($this->list as $key => $route) {
            $this->data = [];
            $this->protected = [];

            if (!empty($route['h']) && !$this->domainMatching($route['h'])) {
                continue;
            }
            if (!empty($route['c'])) {
                $this->fallback = $route['k'];
                $fallbackNumber = $key;
                continue;
            }
            if (!empty($route['m'])) {
                $addressParts or $addressParts = $this->addressSeparation($address);
                $routeParts = $this->addressSeparation($route['a']);


                if ($this->checkVariableRoute($addressParts, $routeParts)) {
                    $this->setData($route);
                    return $route['k'];
                }
            }


            if (\trim($route['a'], '?') === $address) {
                $this->setData($route);
                return $route['k'];
            }


            if ((empty($route['d']) && empty($route['v'])) || empty($route['n'])) {


                if (isset($route['f']) && $firstPart !== $route['f']) {
                    continue;
                }
            }

            $addressParts or $addressParts = $this->addressSeparation($address);


            if (empty($route['d']) && !empty($route['v']) && $address === $route['a'] . '/' . \end($addressParts)) {
                $this->setData($route);
                return $route['k'];
            }

            $routeParts = $this->addressSeparation($route['a']);
            $countRouteParts = \count($routeParts);
            $countAddressParts = \count($addressParts);

            $this->isCompleteAddress = $countRouteParts === $countAddressParts;


            if (empty($route['v']) && !$this->isCompleteAddress) {
                continue;
            }


            if (!empty($route['v']) && (!$this->isCompleteAddress && $countRouteParts !== $countAddressParts + 1)) {
                continue;
            }

            $data = [];
            $search = false;
            foreach ($routeParts as $index => $part) {
                $param = \trim($part, '{?}');


                if (!isset($addressParts[$index])) {
                    $search = \str_contains($part, '?');
                    if ($search && \str_contains($part, '{')) {


                        $data[$param] = null;
                    }
                    break;
                }
                $addressPart = $addressParts[$index];


                if (\str_starts_with($part, '@')) {
                    if (!\str_starts_with($addressPart, '@')) {
                        $search = false;
                        break;
                    }
                    $addressPart = \substr($addressPart, 1);
                    $part = \substr($part, 1);
                }

                if (\str_contains($part, '{')) {
                    if (!empty($route['w'][$param])) {
                        if (\str_starts_with($route['w'][$param], '/')) {
                            if (!\preg_match($route['w'][$param], $addressPart)) {
                                $search = false;
                                break;
                            }
                        } else if (!\preg_match('/^' . $route['w'][$param] . '$/u', $addressPart)) {
                            $search = false;
                            break;
                        }
                    }


                    $data[$param] = $addressPart;
                } else if (\rtrim($part, '?') !== $addressPart) {
                    $search = false;
                    break;
                }
                $search = true;
            }
            if ($search) {
                $this->setData($route, $data);
                return $route['k'];
            }
        }

        if ($this->fallback) {
            $this->setData($this->list[$fallbackNumber]);
        }

        return $this->fallback ?: false;
    }

    private function setData(array $route, array $data = []): void
    {
        $this->data = $this->updateData($data);
        $this->routeName = $route['i'] ?? null;
        if (!empty($route['p'])) {
            $this->protected = $route['p'];
        }
        if (isset($route['b'])) {
            $this->isPlain = (bool)$route['b'];
        }
        if (isset($route['u'])) {
            $this->isNoDebug = (bool)$route['u'];
        }
    }

    private function updateData(array $data): array
    {
        $result = [];
        foreach($data as $key => $value) {
            if (is_string($key)) {
                $key = \trim($key, '@{}');
            }
            $result[$key] = $value;
        }
        return $result;
    }

    private function addressSeparation(string $address): array
    {
        if ($address === '/') {
            return [''];
        }
        return \explode('/', $address);
    }

    private function withIndex(string $address): string
    {
        return $address !== '' ? $address : '/';
    }

    private function checkVariableRoute(array $addressParts, array $routeParts): bool
    {
        $exactPart = \ltrim(\array_pop($routeParts), '.');
        if (\count($routeParts) > \count($addressParts)) {
            return false;
        }
        $parts = \array_slice($addressParts, 0, \count($routeParts));
        if (\implode('/', $parts) !== \implode('/', $routeParts)) {
            return false;
        }
        $result = (new RangeChecker($exactPart))->check(\count($addressParts) - \count($routeParts));
        if ($result) {
            $this->data = $this->updateData(array_values(array_slice($addressParts, \count($routeParts) - 1)));
        }

        return $result;
    }

    private function domainMatching(array $data): bool
    {
        $domain = $this->request->getUri()->getHost();
        $parts = \array_reverse(explode('.', \strstr($domain, ':', true) ?: $domain));
        $max = \max(\array_keys($data));
        $countParts = \count($parts);
        if ($countParts < $max || ($countParts > $max && !\in_array('*', $data[$max], true))) {
            return false;
        }
        foreach ($data as $level => $rules) {
            $level = (int)$level - 1;
            $level < 0 and $level = 0;


            $item = $parts[$level] ?? [];
            if ($item) {
                $isRegExp = false;


                foreach ($rules as $rule) {
                    if (\str_starts_with($rule, '/') && \preg_match($rule, $item)) {
                        $isRegExp = true;
                        break;
                    }
                }
                if ($isRegExp) {
                    continue;
                }
                if (!\in_array($item, $rules, true)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }
}
