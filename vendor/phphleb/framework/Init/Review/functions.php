<?php

declare(strict_types=1);

use Hleb\Constructor\Data\View;
use Hleb\Static\View as StaticView;
use Hleb\HttpMethods\External\RequestUri;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Console\Specifiers\ArgType;
use Hleb\Main\Logger\LoggerWrapper;
use Hleb\Reference\LogInterface;
use Hleb\Static\Cache;
use Hleb\Static\Csrf;
use Hleb\Static\Debug;
use Hleb\Static\Once;
use Hleb\Static\Path;
use Hleb\Static\Redirect;
use Hleb\Static\Request;
use Hleb\Static\Router;
use Hleb\Static\Script;
use Hleb\Static\Settings;
use Hleb\Static\Template;
use JetBrains\PhpStorm\NoReturn;

if (!function_exists('hl_debug')) {

    function hl_debug(): bool
    {
        return Settings::isDebug();
    }
}

if (!function_exists('hl_db_config')) {

    function hl_db_config(string $key): mixed
    {
        return Settings::getParam('database', $key);
    }
}

if (!function_exists('hl_db_connection')) {

    function hl_db_connection(string $name): array
    {
        $connection = hl_db_config('db.settings.list')[$name] ?? null;
        if (!$connection || !\is_array($connection)) {
            throw new InvalidArgumentException('Connection not found: ' . $name);
        }
        return $connection;
    }
}

if (!function_exists('hl_db_active_connection')) {

    function hl_db_active_connection(): array
    {
        return hl_db_connection(hl_db_config('base.db.type'));
    }
}

if (!function_exists('hl_realpath')) {


    function hl_realpath(string $keyOrPath): string|false
    {
        return Path::getReal($keyOrPath);
    }
}

if (!function_exists('hl_path')) {

    function hl_path(string $keyOrPath): string|false
    {
        return Path::get($keyOrPath);
    }
}

if (!function_exists('is_async')) {

    function is_async(): bool
    {
        return Settings::isAsync();
    }
}

if (!function_exists('hl_is_async')) {

    function hl_is_async(): bool
    {
        return Settings::isAsync();
    }
}

if (!function_exists('async_exit')) {

    #[NoReturn]
    function async_exit($message = '', ?int $httpStatus = null, array $headers = []): never
    {
        Script::asyncExit($message, $httpStatus, $headers);
    }
}

if (!function_exists('hl_async_exit')) {

    #[NoReturn]
    function hl_async_exit($message = '', ?int $httpStatus = null, array $headers = []): never
    {
        Script::asyncExit($message, $httpStatus, $headers);
    }
}

if (!function_exists('view')) {

    function view(string $template, array $params = [], ?int $status = null): View
    {
        return StaticView::view($template, $params, $status);
    }
}

if (!function_exists('hl_view')) {

    function hl_view(string $template, array $params = [], ?int $status = null): View
    {
        return StaticView::view($template, $params, $status);
    }
}

if (!function_exists('csrf_token')) {

    function csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('hl_csrf_token')) {

    function hl_csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('csrf_field')) {

    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('hl_csrf_field')) {

    function hl_csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('template')) {

    function template(string $viewPath, array $extractParams = [], array $config = []): string
    {

        return Template::get($viewPath, $extractParams, $config);
    }
}

if (!function_exists('hl_template')) {

    function hl_template(string $viewPath, array $extractParams = [], array $config = []): string
    {
        return Template::get($viewPath, $extractParams, $config);
    }
}

if (!function_exists('insertTemplate')) {

    function insertTemplate(string $viewPath, array $extractParams = [], array $config = []): void
    {

        Template::insert($viewPath, $extractParams, $config);
    }
}

if (!function_exists('hl_insert_template')) {

    function hl_insert_template(string $viewPath, array $extractParams = [], array $config = []): void
    {
        Template::insert($viewPath, $extractParams, $config);
    }
}

if (!function_exists('insertCacheTemplate')) {

    function insertCacheTemplate(string $viewPath, array $extractParams = [], int $sec = Cache::DEFAULT_TIME, array $config = []): void
    {

        Template::insertCache($viewPath, $extractParams, $sec, $config);
    }
}

if (!function_exists('hl_insert_cache_template')) {

    function hl_insert_cache_template(string $viewPath, array $extractParams = [], int $sec = Cache::DEFAULT_TIME, array $config = []): void
    {
        Template::insertCache($viewPath, $extractParams, $sec, $config);
    }
}

if (!function_exists('url')) {

    function url(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): string
    {
        return Router::url($routeName, $replacements, $endPart, $method);
    }
}

if (!function_exists('hl_url')) {

    function hl_url(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): string
    {
        return Router::url($routeName, $replacements, $endPart, $method);
    }
}

if (!function_exists('address')) {

    function address(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): string
    {
        return Router::address($routeName, $replacements, $endPart, $method);
    }
}

if (!function_exists('hl_address')) {

    function hl_address(string $routeName, array $replacements = [], bool $endPart = true, string $method = 'get'): string
    {
        return Router::address($routeName, $replacements, $endPart, $method);
    }
}

if (!function_exists('Arg')) {

    function Arg(?string $name): ArgType
    {
        return new ArgType($name);
    }
}

if (!function_exists('print_r2')) {

    function print_r2(mixed $data, ?string $name = null): void
    {
        Debug::send($data, $name);
    }
}

if (!function_exists('hl_print_r2')) {

    function hl_print_r2(mixed $data, ?string $name = null): void
    {
        Debug::send($data, $name);
    }
}

if (!function_exists('var_dump2')) {

    function var_dump2(mixed $value, mixed ...$values): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
            echo '<pre>' . PHP_EOL;
            \var_dump($value, ...$values);
            echo PHP_EOL . '</pre>';
            return;
        }
        \var_dump($value, ...$values);
    }
}

if (!function_exists('hl_var_dump2')) {

    function hl_var_dump2(mixed $value, mixed ...$values): void
    {
        var_dump2($value, ...$values);
    }
}

if (!function_exists('dump')) {

    function dump(mixed $value, mixed ...$values): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {

            echo PHP_EOL, \core_formatting_debug_info($value, ...$values), PHP_EOL;
        } else {
            \var_dump($value, ...$values);
        }
    }
}

if (!function_exists('hl_dump')) {

    function hl_dump(mixed $value, mixed ...$values): void
    {
        dump($value, ...$values);
    }
}

if (!function_exists('dd')) {

    #[NoReturn]
    function dd(mixed $value, mixed ...$values): never
    {
        \dump($value, ...$values);

        \async_exit();
    }
}

if (!function_exists('hl_dd')) {

    #[NoReturn]
    function hl_dd(mixed $value, mixed ...$values): never
    {
        \dd($value, ...$values);
    }
}

if (!function_exists('route_name')) {

    function route_name(): null|string
    {
        return Router::name();
    }
}

if (!function_exists('hl_route_name')) {

    function hl_route_name(): null|string
    {
        return Router::name();
    }
}

if (!function_exists('param')) {

    function param(string $name): DataType
    {
        return Request::param($name);
    }
}

if (!function_exists('hl_param')) {

    function hl_param(string $name): DataType
    {
        return Request::param($name);
    }
}

if (!function_exists('setting')) {

    function setting(string $key): mixed
    {
        return Settings::getParam('main', $key);
    }
}

if (!function_exists('hl_setting')) {

    function hl_setting(string $key): mixed
    {
        return Settings::getParam('main', $key);
    }
}

if (!function_exists('config')) {

    function config(string $name, string $key): mixed
    {
        return Settings::getParam($name, $key);
    }
}

if (!function_exists('hl_config')) {

    function hl_config(string $name, string $key): mixed
    {
        return Settings::getParam($name, $key);
    }
}

if (!function_exists('get_config_or_fail')) {

    function get_config_or_fail(string $name, string $key): mixed
    {
        return config($name, $key) ?? throw new InvalidArgumentException("Failed to get `{$key}` parameter from `{$name}` configuration");
    }
}

if (!function_exists('hl_get_config_or_fail')) {

    function hl_get_config_or_fail(string $name, string $key): mixed
    {
        return get_config_or_fail($name, $key);
    }
}

if (!function_exists('hl_redirect')) {

    #[NoReturn]
    function hl_redirect(string $location, int $status = 302): void
    {
        Redirect::to($location, $status);
    }
}

if (!function_exists('request_uri')) {

    function request_uri(): RequestUri
    {
        return Request::getUri();
    }
}

if (!function_exists('request_host')) {

    function request_host(): string
    {
        return Request::getUri()->getHost();
    }
}

if (!function_exists('hl_request_host')) {

    function hl_request_host(): string
    {
        return Request::getUri()->getHost();
    }
}

if (!function_exists('request_path')) {

    function request_path(): string
    {
        return Request::getUri()->getPath();
    }
}

if (!function_exists('hl_request_path')) {

    function hl_request_path(): string
    {
        return Request::getUri()->getPath();
    }
}

if (!function_exists('request_address')) {

    function request_address(): string
    {
        return Request::getAddress();
    }
}

if (!function_exists('hl_request_address')) {

    function hl_request_address(): string
    {
        return Request::getAddress();
    }
}

if (!function_exists('logger')) {

    function logger(): LogInterface
    {
        return new LoggerWrapper();
    }
}

if (!function_exists('hl_logger')) {

    function hl_logger(): LogInterface
    {
        return new LoggerWrapper();
    }
}

if (!function_exists('hl_file_exists')) {

    function hl_file_exists(string $path): bool
    {
        return Path::exists($path);
    }
}

if (!function_exists('hl_file_get_contents')) {

    function hl_file_get_contents(string $path, bool $use_include_path = false, $context = null, int $offset = 0, ?int $length = null): false|string
    {
        return Path::contents($path, $use_include_path, $context, $offset, $length);
    }
}

if (!function_exists('hl_file_put_contents')) {

    function hl_file_put_contents(string $path, mixed $data, int $flags = 0, $context = null): false|int
    {
        return Path::put($path, $data, $flags, $context);
    }
}

if (!function_exists('hl_is_dir')) {

    function hl_is_dir(string $path): bool
    {
        return Path::isDir($path);
    }
}

if (!function_exists('hl_relative_path')) {

    function hl_relative_path(string $path): string
    {
        return Path::relative($path);
    }
}

if (!function_exists('hl_create_directory')) {

    function hl_create_directory(string $path, int $permissions = 0775): bool
    {
        return Path::createDirectory($path, $permissions);
    }
}

if (!function_exists('is_empty')) {

    function is_empty(mixed $value): bool
    {
        return $value === null || $value === [] || $value === '' || $value === false;
    }
}

if (!function_exists('hl_is_empty')) {

    function hl_is_empty(mixed $value): bool
    {
        return is_empty($value);
    }
}

if (!function_exists('once')) {

    function once(callable $func): mixed
    {
        return Once::get($func);
    }
}

if (!function_exists('hl_once')) {

    function hl_once(callable $func): mixed
    {
        return Once::get($func);
    }
}


if (!function_exists('preview')) {

    function preview(string $value): string
    {
        return Functions::PREVIEW_TAG . $value;
    }
}
