<?php

namespace Hleb\Constructor\Containers;

use Hleb\Reference\ArrInterface;
use Hleb\Reference\CacheInterface;
use Hleb\Reference\CommandInterface;
use Hleb\Reference\ConverterInterface;
use Hleb\Reference\CsrfInterface;
use Hleb\Reference\DbInterface;
use Hleb\Reference\DebugInterface;
use Hleb\Reference\DtoInterface;
use Hleb\Reference\LogInterface;
use Hleb\Reference\PathInterface;
use Hleb\Reference\RedirectInterface;
use Hleb\Reference\SessionInterface;
use Hleb\Reference\CookieInterface;
use Hleb\Reference\RequestInterface;
use Hleb\Reference\ResponseInterface;
use Hleb\Reference\RouterInterface;
use Hleb\Reference\SettingInterface;
use Hleb\Reference\SystemInterface;
use Hleb\Reference\TemplateInterface;

interface CoreContainerInterface
{

    public function get(string $id): mixed;

    public function has(string $id): bool;

    public function arr(): ArrInterface;

    public function path(): PathInterface;

    public function redirect(): RedirectInterface;

    public function csrf(): CsrfInterface;

    public function cookies(): CookieInterface;

    public function request(): RequestInterface;

    public function response(): ResponseInterface;

    public function settings(): SettingInterface;

    public function route(): RouterInterface;

    public function dto(): DtoInterface;

    public function session(): SessionInterface;

    public function debug(): DebugInterface;

    public function log(): LogInterface;

    public function db(): DbInterface;

    public function system(): SystemInterface;

    public function converter(): ConverterInterface;

    public function cache(): CacheInterface;

    public function template(): TemplateInterface;

    public function command(): CommandInterface;
}
