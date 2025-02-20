<?php


namespace Hleb\Main;

use App\Bootstrap\Events\KernelEvent;
use AsyncExitException;
use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Constructor\DI\DependencyInjection;
use Hleb\CoreProcessException;
use Hleb\Helpers\ReflectionMethod;
use Hleb\Helpers\RouteHelper;
use Hleb\HttpException;
use Hleb\HttpMethods\External\SystemRequest;
use Hleb\HttpMethods\Intelligence\Cookies\AsyncCookies;
use Hleb\Main\Routes\Search\RouteAsyncFileManager;
use \Hleb\Static\Csrf;
use Hleb\HttpMethods\Intelligence\AsyncConsolidator;
use Hleb\HttpMethods\Intelligence\Cookies\StandardCookies;
use Hleb\Static\Request;
use Hleb\Static\Response;
use Hleb\Main\Routes\Search\RouteFileManager;
use Hleb\Main\System\LibraryServiceResources;
use Phphleb\Debugpan\InitPanel;
use Phphleb\Hlogin\App\Content\ScriptLoader;
use App\Middlewares\Hlogin\Registrar;

final class ProjectLoader
{
    private static array $cachePlainRoutes = [];

    private static ?bool $kernelEventExists = null;

    public static function init(): void
    {

        if (self::cachePlainRoutes() || self::insertServiceResource()) {
            return;
        }


        $routes = SystemSettings::isAsync() ? new RouteAsyncFileManager() : new RouteFileManager();


        $block = $routes->getBlock();


        if (self::searchHeadMethod()) {
            return;
        }


        self::checkIsNoDebug($routes, DynamicParams::getRequest());

        if ($block) {
            if (self::searchDefaultHttpOptionsMethod($block)) {
                return;
            }


            self::updateDataIfModule($block);


            if (self::initBlock($block, $routes)){
                return;
            }
        }

        if ($routes->isBlocked()) {
            (new BaseErrorPage(403, 'Locked resource'))->insertInResponse();
            return;
        }

        $block = $routes->getFallbackBlock();
        if ($block && self::initBlock($block, $routes)) {
            return;
        }
        unset($block, $routes);


        (new BaseErrorPage(404, 'Not Found'))->insertInResponse();
        self::addDebugPanelToResponse();
    }

    public static function renderSimpleValue(string $value, string $address): array
    {
        $isSimple = false;
        $contentType = 'text/html';
        if (\str_starts_with($value, \Functions::PREVIEW_TAG)) {
            $value = \substr($value, \strlen(\Functions::PREVIEW_TAG));
            if (\str_contains($value, '{')) {
                $replacements = [
                    '{{ip}}' => DynamicParams::getRequest()->getUri()->getIp(),
                    '{{method}}' => DynamicParams::getRequest()->getMethod(),
                    '{{route}}' => $address,
                ];
                if (\str_contains($value, '%')) {
                    foreach (DynamicParams::getDynamicUriParams() as $key => $param) {
                        if ("{%$key%}" === $value) {
                            $value = $param;
                            $isSimple = true;
                            $replacements = [];
                            break;
                        }
                        $replacements["{%$key%}"] = (string)$param;
                    }
                } else if(!\str_contains($value, '{{ip}}')){
                    $isSimple = true;
                }
                $replacements and $value = \strtr($value, $replacements);
            }
            if (DynamicParams::isDebug()) {
                $value = \htmlspecialchars($value);
            } else if (\str_starts_with($value, '{') && \str_ends_with($value, '}')) {
                $contentType = 'application/json';
            } else {
                $contentType = 'text/plain';
            }
            Response::addHeaders(['Content-Type' => $contentType]);
        } else {
            $isSimple = true;
        }

        Response::addToBody($value);

        if (!DynamicParams::isDebug() && SystemSettings::isAsync()) {
            if ($isSimple) {
                return [
                    'id' => DynamicParams::addressAsString(true),
                    'value' => $value,
                    'type' => $contentType,
                ];
            }
        }
        return [];
    }

    private static function updateDataIfModule(array $block): void
    {
        if (isset($block['module'])) {
            $moduleName = $block['module']['name'];
            $mainFile = SystemSettings::getRealPath("@modules/$moduleName/config/main.php");
            if ($mainFile) {
                $main = (static function () use ($mainFile): array {
                    return require $mainFile;
                })();
                SystemSettings::updateMainSettings($main);
            }
            SystemSettings::addModuleType((bool)SystemSettings::getRealPath("@modules/$moduleName/views"));

            $dbFile = SystemSettings::getRealPath("@modules/$moduleName/config/database.php");
            if ($dbFile) {
                $database = (static function () use ($dbFile): array {
                    return require $dbFile;
                })();
                SystemSettings::updateDatabaseSettings($database);
            }
        }
    }

    private static function addDebugPanelToResponse(): void
    {
        if (DynamicParams::isDebug() &&
            DynamicParams::getRequest()->getMethod() === 'GET' &&
            SystemSettings::getRealPath('@library/debugpan')
        ) {
            Response::addToBody((new InitPanel())->createPanel());
        }
    }

    private static function addRegisterBlockIfExists(): void
    {
        if (\class_exists(Registrar::class, false) && Registrar::isUsed()) {
            ScriptLoader::set();
        }
    }

    private static function insertServiceResource(): bool
    {
        $address = DynamicParams::getRequest()->getUri()->getPath();
        if (\str_starts_with($address, '/hl') && !\str_contains($address, '.')) {
            self::initCookies(true);
            self::initSession(true);
            return (new LibraryServiceResources())->place();
        }
        return false;
    }

    private static function initCookies(bool|null $disabledInRoute): void
    {
        if ($disabledInRoute === true) {
            return;
        }
        $cookies = DynamicParams::getAlternateCookies();
        if (\is_array($cookies)) {
            foreach ($cookies as $name => $cookie) {
                AsyncCookies::set($name, $cookie);
            }
            $_COOKIE = $cookies;
        }
    }

    private static function initSession(bool|null $disabledInRoute): void
    {
        if ($disabledInRoute === true) {
            return;
        }
        $session = DynamicParams::getAlternateSession();
        if (\is_array($session)) {
            self::updateSession($session);
            DynamicParams::setAlternateSession($session);
            $_SESSION = $session;
            return;
        }



        if ($disabledInRoute === false || SystemSettings::getValue('main', 'session.enabled')) {
            if (SystemSettings::isStandardMode()) {
                if (\session_status() !== PHP_SESSION_ACTIVE) {
                    \session_name(SystemSettings::getSystemValue('session.name'));
                    $options = SystemSettings::getMainValue('session.options');
                    if ($options){
                        \session_set_cookie_params($options);
                    } else {
                        $lifetime = SystemSettings::getSystemValue('max.session.lifetime');
                        if ($lifetime > 0) {
                            \session_set_cookie_params($lifetime);
                        }
                    }
                }
                if (!\session_id()) {
                    \session_start();
                }
                StandardCookies::sync();
            } else {
               AsyncConsolidator::initAsyncSessionAndCookies();
            }
            if (\session_status() !== PHP_SESSION_ACTIVE) {
                throw new CoreProcessException('SESSION not initialized!');
            }
        }
        empty($_SESSION) or self::updateSession($_SESSION);
    }

    private static function updateSession(array &$session): void
    {
        $id = '_hl_flash_';
        if (isset($session[$id])) {
            foreach ($session[$id] as $key => &$data) {
                $data['reps_left'] --;
                if ($data['reps_left'] < 0) {
                    unset($session[$id][$key]);
                    continue;
                }
                if (isset($data['new'])) {
                    $data['old'] = $data['new'];
                    $data['new'] = null;
                }
                if (\is_null($data['old'])) {
                    unset($session[$id][$key]);
                }
            }
        }
    }

    private static function searchHeadMethod(): bool
    {
        if (DynamicParams::getRequest()->getMethod() === 'HEAD') {
            $allow = (new RouteHelper())->getRouteHttpMethods(
                Request::getUri()->getPath(),
                Request::getHost(),
            );
            if (\count($allow) > 2) {
                Response::setBody('');
                Response::setStatus(200);
                return true;
            }
        }
        return false;
    }

    private static function searchDefaultHttpOptionsMethod(array $block): bool
    {
        if ($block['name'] !== 'options' &&
            DynamicParams::getRequest()->getMethod() === 'OPTIONS'
        ) {
            $allow = (new RouteHelper())->getRouteHttpMethods(
                Request::getUri()->getPath(),
                Request::getHost(),
            );
            Response::replaceHeaders([
                'Allow' => implode(', ', $allow),
                'Content-Length' => '0',
            ]);
            Response::setBody('');
            Response::setStatus(200);
            return true;
        }
        return false;
    }

    private static function cachePlainRoutes(): bool
    {
        if (self::$cachePlainRoutes) {
            if (self::searchKernelEvent()) {
                return false;
            }
            $cache = self::$cachePlainRoutes[DynamicParams::addressAsString(true)] ?? [];
            if ($cache) {
                Response::setBody($cache['value']);
                Response::addHeaders(['Content-Type' => $cache['type']]);
                return true;
            }
        }
        return false;
    }

    private static function addToPlainCache(array $data): void
    {
        if (!$data) {
            return;
        }
        if (\count(self::$cachePlainRoutes) > 1000) {
            \array_unshift(self::$cachePlainRoutes);
        }
        $id = $data['id'];
        unset($data['id']);
        self::$cachePlainRoutes[$id] = $data;
    }

    private static function initBlock(array $block, RouteFileManager $routes): bool
    {
        self::initCookies($routes->getIsPlain());
        self::initSession($routes->getIsPlain());

        DynamicParams::setDynamicUriParams($routes->getData());
        DynamicParams::setRouteName($routes->getRouteName());
        DynamicParams::setRouteClassName($routes->getRouteClassName());

        if (($protected = $routes->protected()) && \in_array('CSRF', $protected, true) && !Csrf::validate(Csrf::discover())) {
            (new BaseErrorPage(401, 'Protected from CSRF'))->insertInResponse();
            return true;
        }
        if (self::searchKernelEvent() && self::runKernelEventAndExit()) {
            return true;
        }


        if (empty($block['middlewares']) && empty($block['middleware-after']) && \is_string($block['data']['view'] ?? null)) {
            self::addToPlainCache(self::renderSimpleValue($block['data']['view'], $block['full-address']));
            DynamicParams::setEndTime(\microtime(true));
            self::addDebugPanelToResponse();
            return true;
        }

        $workspace = new Workspace();
        DynamicParams::setCoreEndTime(\microtime(true));
        $result = $workspace->extract($block);
        if ($result) {
            self::addRegisterBlockIfExists();
            DynamicParams::setEndTime(\microtime(true));
            self::addDebugPanelToResponse();
            return true;
        }
        DynamicParams::setEndTime(\microtime(true));

        return false;
    }

    private static function searchKernelEvent(): bool
    {
        if ((SystemSettings::getSystemValue('events.used') ?? true) !== false) {
            if (\is_null(self::$kernelEventExists)) {
                $file = SystemSettings::getPath('@global/app/Bootstrap/Events/KernelEvent.php');
                self::$kernelEventExists = \file_exists($file);
                if (self::$kernelEventExists) {
                    require $file;
                }
            }
        }
        return (bool)self::$kernelEventExists;
    }

    protected static function runKernelEventAndExit(): bool
    {
        if (self::$kernelEventExists) {
            if (\method_exists(KernelEvent::class, '__construct')) {
                $refConstruct = new ReflectionMethod(KernelEvent::class, '__construct');
                $event = new KernelEvent(
                    ...($refConstruct->countArgs() ? DependencyInjection::prepare($refConstruct) : [])
                );
            } else {
                $event = new KernelEvent();
            }
            return !$event->before();
        }
        return false;
    }

    protected static function checkIsNoDebug(RouteFileManager $routes, SystemRequest $request): void
    {
        if ($routes->getIsNoDebug() &&
            ($request->getGetParam('_debug') ?? $request->getPostParam('_debug')) !== 'on'
        ) {
            DynamicParams::setDynamicDebug(false);
        }
    }
}
