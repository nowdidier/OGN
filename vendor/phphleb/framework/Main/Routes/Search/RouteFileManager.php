<?php


namespace Hleb\Main\Routes\Search;

use Hleb\Constructor\Cache\CacheRoutes;
use Hleb\Constructor\Cache\RouteMark;
use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\AsyncRouteException;
use Hleb\RouteColoredException;
use Hleb\HttpMethods\External\SystemRequest;
use Hleb\Main\Routes\Update\CheckRouteForUpdates;
use Hleb\Main\Routes\Update\RouteData;

class RouteFileManager
{
    protected bool $isBlocked = false;

    protected array $data = [];

    protected int $fallbackNumber = 0;

    protected array $protected = [];

    protected ?string $method = null;

    protected ?string $routeName = null;

    protected ?string $routeClassName = null;

    protected ?bool $isPlain = null;

    protected ?bool $isNoDebug = null;

    protected static ?array $infoCache = null;

    protected static bool|array $stubData = false;

    public function getBlock(): false|array
    {

        $this->init();
        self::$infoCache = $this->getInfoFromCache();


        if ($this->checkFromUpdate(self::$infoCache)) {


            if ($this->validateInfoFromUpdate(self::$infoCache, $this->getInfoFromCache())) {


                $routes = (new RouteData())->dataExtraction();
                if ((new CacheRoutes($routes))->save() === false) {
                    $this->throwSaveError();
                }
                if (\function_exists('opcache_reset')) {
                    \opcache_reset();
                }
            }


            self::$infoCache = $this->getInfoFromCache();
            if (!self::$infoCache) {
                $this->throwSaveError();
            }
        }


        self::$stubData = $this->siteStubSearch(self::$infoCache);

        if (self::$stubData) {
            $this->isBlocked = true;
            return \is_array(self::$stubData) ? self::$stubData : false;
        }
        self::$infoCache = $this->getInfoFromCache();


        return $this->searchBlock();
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
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

    public function getData(): array
    {
        return $this->data;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function getRouteClassName(): ?string
    {
        return $this->routeClassName;
    }

    public function getFallbackBlock(): array|false
    {
        if (!$this->fallbackNumber) {
            return false;
        }
        return $this->getBlockById($this->fallbackNumber);
    }

    protected function searchBlock(): false|array
    {
        $request = DynamicParams::getRequest();


        $index = $this->searchIndexPage((int)(self::$infoCache['index_page'] ?? 0), $request);
        if ($index) {
            $this->routeName = self::$infoCache['index_page_name'] ?? null;
            $this->isNoDebug = self::$infoCache['no_debug'] ?? null;

            return $index;
        }


        $this->method = \ucfirst(\strtolower($request->getMethod()));


        $block = $this->getDataByRequest($request);


        if ($block === false) {
            return false;
        }

        $blockNumber = $this->createBlockDataNumber($block);
        if (!$blockNumber) {
            return false;
        }


        return $this->getBlockById($blockNumber, $block->getIsCompleteAddress());
    }

    protected function init(): void
    {
        $this->data = [];
        $this->protected = [];
        $this->fallbackNumber = 0;
        $this->method = null;
    }

    private function getBlockById($blockNumber, bool $isComplete = true): false|array
    {
        $method = $this->method;


        $class = RouteMark::getRouteClassName(RouteMark::DATA_PREFIX . $method . $blockNumber);
        $path = $this->searchPath("@storage/cache/routes/Map/$method/$class.php");
        if (empty($path)) {
            return false;
        }

        $data = $this->getFromCache($path, $class);

        if ($data) {
            $default = $data['data']['default'] ?? [];
            if ($default) {
                $this->data += $this->checkKeysAndUpdateData($default, $data['full-address'] ?? 'undefined', $isComplete);
            }
        }
        return $data;
    }

    private function getFromCache(string $path, string $class): array|false
    {
        if (SystemSettings::isAsync()) {


            if (!\class_exists($class, false)) {
                if (!\file_exists($path)) {
                    return false;
                }
                require $path;
            }
        } else {
            if (!\file_exists($path)) {
                return false;
            }
            if (!\class_exists($class, false)) {
                require $path;
            }
        }
        $this->routeClassName = $class;

        return $class::getData();
    }

    private function searchIndexPage(int $page, SystemRequest $request): false|array
    {
        if ($page && $request->getMethod() === 'GET' && $request->getUri()->getPath() === '/') {
            $class = RouteMark::getRouteClassName(RouteMark::DATA_PREFIX . 'Get' . $page);
            $path = $this->searchPath("@storage/cache/routes/Map/Get/$class.php");
            if ($path) {
                return $this->getFromCache($path, $class);
            }
        }

        return false;
    }

    private function searchPath(string $path): false|string
    {
        return SystemSettings::isAsync() ? SystemSettings::getPath($path) : SystemSettings::getRealPath($path);
    }

    private function siteStubSearch(array $info): array|bool
    {
        if ($this->checkForBlocking()) {
            if (!empty($info['site_blocked'])) {
                $class = RouteMark::getRouteClassName(RouteMark::DATA_PREFIX . 'Get' . $info['site_blocked']);
                return $this->getFromCache(
                    SystemSettings::getRealPath("@storage/cache/routes/Map/Get/$class.php"),
                    $class
                );
            }
            return true;
        }
        return false;
    }

    private function checkForBlocking(): bool
    {
        $path = SystemSettings::getRealPath('@storage/cache/routes/lock-status.info');
        if (!$path) {
            return false;
        }
        return (bool)\file_get_contents($path);
    }

    private function checkFromUpdate(array $info): bool
    {
        if (!SystemSettings::getCommonValue('routes.auto-update')) {


            return false;
        }
        $time = $info['time'] ?? 0;
        if (!$time) {


            return true;
        }
        if (!(new CheckRouteForUpdates($time, SystemSettings::getRealPath('routes')))
            ->hasChanges($info['files_hash'] ?? null)
        ) {


            return false;
        }

        return true;
    }

    private function getInfoFromCache(): array
    {
        $info = [];
        $infoClassName = RouteMark::getRouteClassName(RouteMark::INFO_CLASS_NAME);
        $path = SystemSettings::getRealPath("@storage/cache/routes/$infoClassName.php");
        if ($path) {
            $info = $this->getFromCache($path, $infoClassName);
        }
        return $info ?: [];
    }

    private function validateInfoFromUpdate(array $firstInfo, array $secondInfo): bool
    {


        if ((empty($firstInfo) && empty($secondInfo))) {
            return true;
        }


        if ($firstInfo['time'] === $secondInfo['time']) {
            return true;
        }

        return $this->updateRounds($secondInfo);
    }

    private function updateRounds(array $info): bool
    {
        $update = static function ($i) {
            empty($i['update_status']) || $i['update_status'] < \microtime(true) - 1;
        };
        while ($update($info)) {
            \usleep(10000);
            $info = $this->getInfoFromCache();
        }
        return !empty($info['update_status']);
    }

    private function throwSaveError(): void
    {
       throw (new RouteColoredException(AsyncRouteException::HL01_ERROR))->complete(DynamicParams::isDebug());
    }

    private function getDataByRequest(SystemRequest $request): SearchBlock|false
    {
        $class = RouteMark::getRouteClassName(RouteMark::PREVIEW_PREFIX . $this->method);
        $path = $this->searchPath("@storage/cache/routes/Preview/$class.php");
        if (empty($path)) {
            return false;
        }
        return new SearchBlock($request, (array)$this->getFromCache($path, $class));
    }

    private function createBlockDataNumber(SearchBlock $block): false|int
    {


        $blockNumber = (int)$block->getNumber();
        $this->routeName = $block->getRouteName();
        $this->fallbackNumber = $block->getFallback();
        if (!$blockNumber) {
            return false;
        }
        $this->protected = $block->protected();
        $this->isPlain = $block->getIsPlain();
        $this->isNoDebug = $block->getIsNoDebug();
        $this->data = $block->getData();

        return $blockNumber;
    }

    private function checkKeysAndUpdateData(array $data, string $address, bool $isComplete): array
    {
        $defaultList = [];
        foreach ($data as $subarray) {
            $key = $subarray[0];
            if (isset($defaultList[$key]) || isset($this->data[$key])) {
                throw (new RouteColoredException(AsyncRouteException::HL38_ERROR))->complete(DynamicParams::isDebug(), ['key' => $key, 'value' => $subarray[1], 'address' => $address]);
            }
            $defaultList[$key] = !$isComplete && \str_contains($subarray[1], '?') ? null : \rtrim($subarray[1], '?');
        }
        return $defaultList;
    }

}
