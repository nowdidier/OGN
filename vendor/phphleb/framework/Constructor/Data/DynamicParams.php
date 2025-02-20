<?php


namespace Hleb\Constructor\Data;

use Hleb\Base\RollbackInterface;
use Hleb\HttpMethods\External\SystemRequest;
use Hleb\Main\Insert\BaseAsyncSingleton;

final class DynamicParams extends BaseAsyncSingleton implements RollbackInterface
{

    private static bool $dynamicDebug = true;

    private static ?object $dynamicOriginRequest = null;

    private static ?string $dynamicHost = null;

    private static array $dynamicUriParams = [];

    private static ?string $dynamicRequestId = null;

    private static ?SystemRequest $baseRequest = null;

    private static ?array $argv = null;

    private static bool $dynamicEndingUrl = false;

    private static ?float $startTime = null;

    private static ?float $endTime = null;

    private static ?float $coreEndTime = null;

    private static ?string $dynamicActiveModuleName = null;

    private static array $controllerRelatedData = [];

    private static ?string $dynamicRouteName = null;

    private static ?string $dynamicRouteClassName = null;

    private static ?array $alternateSession = null;

    private static ?array $alternateCookies = null;

    private static ?string $controllerMethodName = null;

    public static function initRequest(
        SystemRequest $request,
        bool          $isDebug,
        ?float        $startTime = null,
    ): void
    {
        self::$baseRequest = $request;
        self::setDynamicRequest($request);
        self::setDynamicDebug($isDebug);
        self::setDynamicHost($request->getUri()->getHost());
        self::$startTime = $startTime;
    }

    public static function setAlternateSession(?array $data): void
    {
        self::$alternateSession = $data;
    }

    public static function getAlternateSession(): ?array
    {
        return self::$alternateSession;
    }

    public static function setAlternateCookies(?array $data): void
    {
        self::$alternateCookies = $data;
    }

    public static function getAlternateCookies(): ?array
    {
        return self::$alternateCookies;
    }

    public static function setDynamicDebug(bool $isDebug): void
    {
        self::$dynamicDebug = $isDebug;
    }

    public static function setDynamicOriginRequest(?object $psr7Request): void
    {
        self::$dynamicOriginRequest = $psr7Request;
    }

    public static function getDynamicOriginRequest(): ?object
    {
        return self::$dynamicOriginRequest;
    }

    public static function getStartTime(): ?float
    {
        return self::$startTime;
    }

    public static function getEndTime(): ?float
    {
        return self::$endTime;
    }

    public static function setEndTime(float $endTime): void
    {
        self::$endTime = $endTime;
    }

    public static function getCoreEndTime(): ?float
    {
        return self::$coreEndTime;
    }

    public static function setCoreEndTime(float $endTime): void
    {
        self::$coreEndTime = $endTime;
    }

    public static function setDynamicHost(string $host): void
    {
        self::$dynamicHost = $host;
    }

    public static function setActiveModuleName(string $name): void
    {
        self::$dynamicActiveModuleName = $name;
    }

    public static function setDynamicUriParams(array $data): void
    {
        self::$dynamicUriParams = $data;
    }

    public static function setDynamicRequest(SystemRequest $request): void
    {
        self::$baseRequest = $request;
        $key = SystemSettings::getSystemValue('ending.slash.url');
        $path = $request->getUri()->getPath();
        $default = !($path === '/') && \str_ends_with($path, '/');
        $methods = SystemSettings::getSystemValue('ending.url.methods');
        $method = $request->getMethod();
        if (\in_array($key, [0, 1, '0', '1'], true) &&
            (\in_array(\strtolower($method), $methods, true) ||
                \in_array(\strtoupper($method), $methods, true)
            )) {
            self::$dynamicEndingUrl = (bool)$key;
        } else {
            self::$dynamicEndingUrl = $default;
        }
    }

    public static function setNewDynamicRequestId(): void
    {


        try {
            $data = \random_bytes(16);
            $data[6] = \chr(\ord($data[6]) & 0x0f | 0x40);
            $data[8] = \chr(\ord($data[8]) & 0x3f | 0x80);
            $hash = \vsprintf('%s%s-%s-%s-%s-%s%s%s', \str_split(\bin2hex($data), 4));
        } catch (\Exception) {
            $hash = \substr(\sha1(\microtime() . \rand()), 0, 36);
            $hash[8] = '-';
            $hash[13] = '-';
            $hash[18] = '-';
            $hash[23] = '-';
        }

        self::$dynamicRequestId = $hash;
    }

    public static function isDebug(): bool
    {
        return SystemSettings::getCommonValue('debug') && self::$dynamicDebug;
    }

    public static function isConfigDebug(): bool
    {
        return (bool)(SystemSettings::getCommonValue('config.debug') ?? false);
    }

    public static function getHost(): ?string
    {
        return self::$dynamicHost;
    }

    public static function getDynamicUriParams(): array
    {
        return self::$dynamicUriParams;
    }

    public static function setControllerRelatedData(array $data): void
    {
        self::$controllerRelatedData = $data;
    }

    public static function getControllerRelatedData(): array
    {
        return self::$controllerRelatedData;
    }

    public static function getDynamicRequestId(): ?string
    {
        if (self::$dynamicRequestId === null) {
            self::setNewDynamicRequestId();
        }
        return self::$dynamicRequestId;
    }

    public static function getConsoleCommand(): string
    {
        return \implode(' ', self::$argv ?? []);
    }

    public static function getBaseRequest(): ?SystemRequest
    {
        return self::$baseRequest;
    }

    public static function getArgv(): array
    {
        return self::$argv ?? [];
    }

    public static function setArgv(array $argv): void
    {
        if (SystemSettings::isCli() && self::$argv === null) {
            self::$argv = $argv;
        }
    }

    public static function isEndingUrl(): bool
    {
        return self::$dynamicEndingUrl;
    }

    public static function getRequest(): SystemRequest
    {
        return self::$baseRequest;
    }

    public static function getModuleName(): ?string
    {
        return self::$dynamicActiveModuleName;
    }

    public static function setRouteName(?string $name): void
    {
        self::$dynamicRouteName = $name;
    }

    public static function getRouteName(): ?string
    {
        return self::$dynamicRouteName;
    }

    public static function setRouteClassName(?string $name): void
    {
        self::$dynamicRouteClassName = $name;
    }

    public static function getRouteClassName(): ?string
    {
        return self::$dynamicRouteClassName;
    }

    public static function setControllerMethodName(?string $name): void
    {
        self::$controllerMethodName = $name;
    }

    public static function getControllerMethodName(): ?string
    {
        return self::$controllerMethodName;
    }

    public static function addressAsArray(): array
    {
           return [
               'host' => self::getRequest()->getUri()->getHost(),
               'scheme' => self::getRequest()->getUri()->getScheme(),
               'path' => self::getRequest()->getUri()->getPath(),
               'method' => self::getRequest()->getMethod(),
               'port' => self::getRequest()->getUri()->getPort(),
               'query' => self::getRequest()->getUri()->getQuery(),
           ];
    }

    public static function addressAsString(bool $withMethod = false, bool $withQuery = false): string
    {
        $data = self::addressAsArray();
        $method = $withMethod ? $data['method'] . ' ' : '';
        if ($data['port'] && !\str_contains($data['host'], ':')) {
            $data['host'] = $data['host'] . ':' . $data['port'];
        }
        $query = $withQuery ? $data['query'] : '';

        return "{$method}{$data['scheme']}://{$data['host']}{$data['path']}{$query}";
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$dynamicDebug = true;

        self::$dynamicOriginRequest = null;

        self::$dynamicHost = null;

        self::$dynamicRequestId = null;

        self::$baseRequest = null;

        self::$argv = null;

        self::$dynamicEndingUrl = false;

        self::$startTime = null;

        self::$endTime = null;

        self::$coreEndTime = null;

        self::$dynamicUriParams = [];

        self::$dynamicActiveModuleName = null;

        self::$dynamicRouteName = null;

        self::$controllerRelatedData = [];

        self::$alternateSession = null;

        self::$dynamicRouteClassName = null;

        self::$alternateCookies = null;

        self::$controllerMethodName = null;
    }

}
