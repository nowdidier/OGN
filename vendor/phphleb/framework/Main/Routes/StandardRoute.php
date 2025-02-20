<?php

declare(strict_types=1);

namespace Hleb\Main\Routes;

use Hleb\Helpers\NameConverter;

abstract class StandardRoute
{
    final public const CONTROLLER_TYPE = 'controller';

    final public const MODULE_TYPE = 'module';

    final public const PAGE_TYPE = 'page';

    final public const ADD_TYPE = 'add';

    final public const MIDDLEWARE_TYPE = 'middleware';

    final public const AFTER_TYPE = 'after';

    final public const WHERE_TYPE = 'where';

    final public const NAME_TYPE = 'name';

    final public const DOMAIN_TYPE = 'domain';

    final public const PREFIX_TYPE = 'prefix';

    final public const TO_GROUP_TYPE = 'toGroup';

    final public const END_GROUP_TYPE = 'endGroup';

    final public const GET_SUBTYPE = 'get';

    final public const POST_SUBTYPE = 'post';

    final public const PATCH_SUBTYPE = 'patch';

    final public const PUT_SUBTYPE = 'put';

    final public const DELETE_SUBTYPE = 'delete';

    final public const OPTIONS_SUBTYPE = 'options';

    final public const ANY_SUBTYPE = 'any';

    final public const MATCH_SUBTYPE = 'match';

    final public const FALLBACK_SUBTYPE = 'fallback';

    final public const PROTECT_TYPE = 'protect';

    final public const PLAIN_TYPE = 'plain';

    final public const REDIRECT_TYPE = 'redirect';

    final public const NO_DEBUG_TYPE = 'noDebug';

    private ?NameConverter $nameConverter = null;

    protected function register(array $data): void
    {
        BaseRoute::add($data);
    }

    protected function getControllerAttributes(string $target, ?string $method = null): array
    {
        return $this->searchClassAndMethod($target, $method);
    }

    protected function searchMiddlewareAttributes(string $target, ?string $method = null): array
    {
        return $this->searchClassAndMethod($target, $method);
    }

    protected function searchModuleAttributes(string $name, string $target, ?string $method = null): array
    {
        return $this->searchClassAndMethod($target, $method);
    }

    protected function searchClassAndMethod(string $target, ?string $baseMethod): array
    {
        $class = $target;
        $method = 'index';
        if ($baseMethod) {
            $method = $baseMethod;
        }
        $parts = \explode('@', $target);
        if (isset($parts[1])) {
            [$class, $method] = $parts;
        }

        return [\trim($class, '\\'), $method];
    }

    protected function updateRouteAddress(string $address): string
    {
        $address = \trim($address, " \\/\t\n\r\0\x0B");
        if ($address === '') {
            return '/';
        }
        return \str_replace('\\', '/', $address);
    }

    protected function getDefaultValues(string &$address): array
    {
        if (!\str_contains($address, ':')) {
            return [];
        }
        $parts = \explode('/', $address);
        $values = [];
        foreach ($parts as &$part) {
            $clearedValue = \trim($part, '{}');
            if (\substr_count($part, ':') === 1 && $part === "{{$clearedValue}}") {
                $params = \explode(':', $clearedValue);
                $values[] = $params;
                $part = $params[1];
            }
        }
        $address = \implode('/', $parts);

        return $values;
    }
}
