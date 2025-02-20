<?php

declare(strict_types=1);

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Data\View;
use Hleb\HlebBootstrap;
use Hleb\Route\Any;
use Hleb\Route\Delete;
use Hleb\Route\Fallback;
use Hleb\Route\Get;
use Hleb\Route\Group\EndGroup;
use Hleb\Route\Group\ToGroup;
use Hleb\Route\MatchTypes;
use Hleb\Route\Options;
use Hleb\Route\Patch;
use Hleb\Route\Post;
use Hleb\Route\Put;

#[Accessible]
final class Route
{
    private function __construct()
    {
    }

    public static function get(string $route, null|int|float|string|View $view = null): Get
    {
        return new Get($route, $view);
    }

    public static function post(string $route, null|int|float|string|View $view = null): Post
    {
        return new Post($route, $view);
    }

    public static function put(string $route, null|int|float|string|View $view = null): Put
    {
        return new Put($route, $view);
    }

    public static function delete(string $route, null|int|float|string|View $view = null): Delete
    {
        return new Delete($route, $view);
    }

    public static function patch(string $route, null|int|float|string|View $view = null): Patch
    {
        return new Patch($route, $view);
    }

    public static function options(string $route): Options
    {
        return new Options($route);
    }

    public static function any(string $route, null|int|float|string|View $view = null): Any
    {
        return new Any($route, $view);
    }

    public static function match(array $types, string $route, null|int|float|string|View $view = null): MatchTypes
    {
        return new MatchTypes($types, $route, $view);
    }

    public static function toGroup(): ToGroup
    {
        return new ToGroup();
    }

    public static function endGroup(): EndGroup
    {
        return new EndGroup();
    }

    public static function fallback(null|int|float|string|View $view = null, array $httpTypes = HlebBootstrap::HTTP_TYPES): Fallback
    {
        return new Fallback($view, $httpTypes);
    }

}
