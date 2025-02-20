<?php


declare(strict_types=1);

namespace Hleb;

use AsyncExitException;
use Exception;
use Functions;
use Hleb\Constructor\Data\{DebugAnalytics, DynamicParams, SystemSettings};
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\HttpMethods\External\RequestUri;
use Hleb\Init\{AddressBar, Autoloader, Connectors\HlebConnector, ErrorLog};
use Hleb\Main\Insert\{BaseAsyncSingleton, BaseSingleton};
use Hleb\Static\Response;
use Hleb\Main\Logger\{FileLogger, Log, LoggerInterface, LogLevel};
use Hleb\Main\ProjectLoader;
use Phphleb\Idnaconv\IdnaConvert;
use Hleb\HttpMethods\External\SystemRequest;
use Hleb\HttpMethods\External\Response as SystemResponse;
use Throwable;

#[Accessible] #[AvailableAsParent]
class HlebBootstrap
{
    public const HTTP_TYPES = ['GET', 'POST', 'DELETE', 'PUT', 'PATCH', 'OPTIONS', 'HEAD'];

    final public const STANDARD_MODE = 1;

    final public const CONSOLE_MODE = 2;

    final public const ASYNC_MODE = 3;

    final protected const DEFAULT_RE_CLEANING = 100_000;

    protected ?int $mode = null;

    protected array $config = [];

    protected ?array $session = null;

    protected ?array $cookies = null;

    private readonly ?string $globalDirectory;

    private readonly ?string $vendorDirectory;

    private readonly ?string $moduleDirectory;

    private ?string $publicDirectory;

    protected ?LoggerInterface $logger = null;

    protected ?IdnaConvert $hostConvertor = null;

    protected ?SystemResponse $response = null;

    protected static bool $loadResources = false;

    protected ?AddressBar $addressBar = null;

    public function __construct(?string $publicPath = null, array $config = [], ?LoggerInterface $logger = null)
    {
        $this->mode === null and $this->mode = self::STANDARD_MODE;


        $this->publicDirectory = $publicPath;


        \defined('HLEB_CORE_VERSION') or \define('HLEB_CORE_VERSION', '2.0.63');

        $this->logger = $logger;


        $this->setErrorHandler();

        if ($config) {
            $this->config = $this->checkConfig($config);
        }

        $this->initialParameters();
    }

    public function setLogger(LoggerInterface $logger): static
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger ?: Log::instance();
    }

    public function load(): int|HlebBootstrap
    {
        try {
            $this->loadProject();

            $this->requestCompletion();

            return $this;

        } catch (AsyncExitException $e) {
            $this->scriptExitEmulation($e);

        } catch (HttpException $e) {
            $this->scriptHttpError($e);

        } catch (Throwable $t) {
            $this->getPreviousErrorControl($t) or $this->scriptErrorHandling($t);
        }

        $this->logsPostProcessing();

        return $this;
    }

    protected function getConfig(): array
    {
        if ($this->config) {
            return $this->config;
        }
        $moduleDirectory = $this->getModuleDirectoryName();

        require __DIR__ . '/Init/Review/basic.php';

        $dir = $this->globalDirectory;
        $func = static function ($path): array {
            return require $path;
        };
        $common = $func($dir . '/config/common.php');
        $database = $func($dir . '/config/database.php');
        $system = $func($dir . '/config/system.php');
        $main = $func($dir . '/config/main.php');

        $system['mode'] = $this->mode;
        if ($moduleDirectory) {
            $system['module.dir.name'] = $moduleDirectory;
        } else {
            $this->moduleDirectory = $this->globalDirectory . DIRECTORY_SEPARATOR . $system['module.dir.name'];
        }
        $system['module.namespace'] = \ucfirst($system['module.dir.name']);
        $paths = $system['project.paths'];
        unset($system['project.paths']);
        foreach ($paths as &$path) {
            $path = $dir . '/' . \ltrim($path, '/\\');
        }
        $c = [
            'common' => $common,
            'main' => $main,
            'database' => $database,
            'default.database' => $database,
            'path' => \array_merge($paths, [
                'global' => $dir,
                'public' => $this->publicDirectory,
                'vendor' => $this->vendorDirectory,
                'modules' => $this->moduleDirectory,
                'app' => $dir . '/app',
                'storage' => $dir . '/storage',
                'routes' => $dir . '/routes',
                'resources' => $dir . '/resources',
                'views' => $dir . '/resources/views',
                'library' => $this->vendorDirectory . '/phphleb',
                'framework' => $this->vendorDirectory . '/phphleb/framework',
            ]),
            'system' => $system
        ];
        if ($custom = $c['system']['custom.setting.files']) {
            foreach ($custom as $name => $file) {
                if ($name && !isset($c[$name]) && \is_string($name)) {
                    $c[$name] = $func($dir . '/' . \ltrim($file, '/\\'));
                }
            }
        }

        return ($this->config = $this->checkConfig($c));
    }

    protected function setDefaultAutoloader(): void
    {


        function agentLoader($class): bool
        {
            \spl_autoload_unregister('Hleb\agentLoader');

            if (\file_exists($vendorDir = \constant('HLEB_VENDOR_DIR') . '/autoload.php')) {
                require_once $vendorDir;
            }
            \spl_autoload_call($class);
            \spl_autoload_unregister('Hleb\reqLoadFunc');
            \spl_autoload_register('Hleb\reqLoadFunc', true, true);

            return \class_exists($class, false);
        }
        \spl_autoload_register('Hleb\agentLoader', true, true);
    }

    protected function searchVendorDirectory(): string
    {
        if (\defined('HLEB_VENDOR_DIR')) {
            return \constant('HLEB_VENDOR_DIR');
        }
        \define('HLEB_VENDOR_DIR', $dir =  \dirname(__DIR__, 2));

        return $dir;
    }

    protected function searchGlobalDirectory(): string
    {
        if (\defined('HLEB_GLOBAL_DIR')) {
            return \constant('HLEB_GLOBAL_DIR');
        }
        $dir = \dirname(__DIR__, 3);
        if (\is_dir($dir . '/app') && \is_dir($dir . '/routes')) {
            return $dir;
        }
        require __DIR__ . '/Init/Connectors/Preload/search-functions.php';

        return (string)search_root();
    }

    protected function getModuleDirectoryName(): ?string
    {
        if (\defined('HLEB_MODULES_DIR')) {
            $this->moduleDirectory = \constant('HLEB_MODULES_DIR');
            return \basename(\constant('HLEB_MODULES_DIR'));
        }
        return null;
    }

    protected function handlingNonExistentMethod(SystemRequest $request): bool
    {
        if (!\in_array($request->getMethod(), self::HTTP_TYPES)) {
            Response::replaceHeaders([
                'Allow' => \implode(', ', \array_unique(self::HTTP_TYPES)),
                'Content-Length' => '0',
            ]);
            Response::setBody('');
            Response::setStatus(501);
            return true;
        }
        return false;
    }

    protected function loadProject(?object $originRequest = null): void
    {
        $startTime = \defined('HLEB_START') ? HLEB_START : \microtime(true);
        $this->config['system']['start.unixtime'] = $startTime;
        SystemSettings::setStartTime($startTime);
        $this->response = null;
        $request = $this->buildRequest($originRequest);
        $debug = (string)($request->getGetParam('_debug') ?? $request->getPostParam('_debug'));
        $debug = $debug !== 'off';
        $this->logger and Log::setLogger($this->logger);
        LogLevel::setDefaultMaxLogLevel(SystemSettings::getCommonValue('max.log.level'));
        DynamicParams::initRequest($request, $debug, $startTime);
        DynamicParams::setAlternateSession($this->session);
        DynamicParams::setAlternateCookies($this->cookies);
        $debug = DynamicParams::isDebug();
        $this->config['common']['debug'] = $debug;
        \date_default_timezone_set($this->config['common']['timezone']);
        \ini_set('display_errors', $debug ? '1' : '0');
        if ($this->config['system']['origin.request']) {
            DynamicParams::setDynamicOriginRequest($originRequest);
        }
        Response::init(new SystemResponse());
        if (SystemSettings::getValue('common', 'show.request.id')) {
            if ($this->mode === self::ASYNC_MODE) {
                Response::addHeaders(['X-Request-ID' => DynamicParams::getDynamicRequestId()]);
            } else {
                \header('X-Request-ID: ' . DynamicParams::getDynamicRequestId());
            }
        }
        if ($this->handlingNonExistentMethod($request)) {
            return;
        }
        if ($this->mode === self::ASYNC_MODE && $request->getMethod() === 'GET') {
            Response::addHeaders(['Content-Type' => 'text/html; charset=UTF-8']);
        }


        $this->verifiedUrlOrRedirect($request);

        ProjectLoader::init();
    }

    protected function output(string $message, ?int $httpCode = null, array $headers = []): void
    {
        if (!Response::getInstance()) {
            Response::init(new SystemResponse());
        }
        Response::setBody($message);
        Response::addHeaders($headers);
        if ($httpCode !== null) {
            Response::setStatus($httpCode);
        }
        if ($this->mode !== self::ASYNC_MODE) {
            $this->headerOutput();
            exit($message);
        }
    }

    protected function requestCompletion(string $content = ''): void
    {
        if (!Response::getInstance()) {
            Response::init(new SystemResponse());
        }

        if ($this->mode === self::ASYNC_MODE) {
            $this->session === null or $this->session = $_SESSION ?? [];
            $this->cookies === null or $this->cookies = $_COOKIE ?? [];
            $this->output($content . Response::getBody());
        } else {
            $this->headerOutput();
            echo $content;
            echo Response::getInstance()->getBody();
        }
    }

    protected function checkConfig(array $config): array
    {
        $map = [
            'common' => [
                'debug' => ['boolean'],
                'log.enabled' => ['boolean'],
                'max.log.level' => ['string'],
                'max.cli.log.level' => ['string'],
                'log.level.in-cli' => ['boolean'],
                'error.reporting' => ['integer'],
                'log.sort' => ['boolean'],
                'log.stream' => ['boolean', 'string'],
                'log.format' => ['string'],
                'log.db.excess' => ['integer'],
                'timezone' => ['string'],
                'routes.auto-update' => ['boolean'],
                'container.mock.allowed' => ['boolean'],
                'app.cache.on' => ['boolean'],
                'show.request.id' => ['boolean'],
                'max.log.size' => ['integer'],
                'max.cache.size' => ['integer'],




            ],
            'database' => [
                'base.db.type' => ['string'],
                'db.settings.list' => ['array'],
            ],
            'main' => [
                'session.enabled' => ['boolean'],
                'db.log.enabled' => ['boolean'],
                'default.lang' => ['string'],
                'allowed.languages' => ['array'],
                'session.options' => ['array'],
            ],
            'system' => [
                'classes.autoload' => ['boolean'],
                'origin.request' => ['boolean'],
                'ending.slash.url' => ['boolean', 'integer'],
                'ending.url.methods' => ['array'],
                'url.validation' => ['boolean', 'string'],
                'session.name' => ['string'],
                'max.session.lifetime' => ['integer'],
                'allowed.route.paths' => ['array'],
                'allowed.structure.parts' => ['array'],
                'page.external.access' => ['boolean'],
                'module.dir.name' => ['string'],
                'custom.setting.files' => ['array'],
                'custom.function.files' => ['array'],






            ],
        ];


        foreach ($map as $key => $rule) {
            if (empty($config[$key])) {
                throw new \DomainException("Configuration not found for `$key`");
            }
            foreach ($rule as $k => $val) {
                if (!isset($config[$key][$k])) {
                    throw new \DomainException("Configuration parameter `$k` not found for `$key`");
                }
                if (!\in_array(\gettype($config[$key][$k]), $val, true)) {
                    throw new \DomainException("Wrong type of configuration parameter `$k`.");
                }
            }
        }
        if (!\in_array($config['main']['default.lang'], $config['main']['allowed.languages'])) {
            throw new \DomainException("The `default.lang` param must be present in the `allowed.languages`.");
        }
        foreach ($config['main']['allowed.languages'] as $lang) {
            if (\strtolower($lang) !== $lang) {
                throw new \DomainException("Only lowercase is allowed for `allowed.languages`");
            }
        }
        if (!\in_array($config['common']['log.format'], ['row', 'json'])) {
            throw new \DomainException("Wrong `log.format` format");
        }

        $config['common']['config.debug'] = $config['common']['debug'];

        return $config;
    }

    protected function buildRequest(?object $request = null): SystemRequest
    {
        $_SERVER['HTTP_HOST'] = $this->convertHost($_SERVER['HTTP_HOST']);

        $this->standardization();
        $protocol = \trim(\stristr($_SERVER["SERVER_PROTOCOL"], '/') ?: '', ' /') ?: '1.1';

        return new SystemRequest(
            $_COOKIE,
            null,
            null,
            null,
            $_SERVER['REQUEST_METHOD'],
            \hl_convert_standard_headers(\getallheaders()),
            $protocol,
            new RequestUri(
                $_SERVER['HTTP_HOST'],
                $_SERVER['DOCUMENT_URI'],
                $_SERVER['QUERY_STRING'],
                $_SERVER['SERVER_PORT'],
                $_SERVER['REQUEST_SCHEME'],
                $_SERVER['REMOTE_ADDR'],
            ));
    }

    protected function standardization(): void
    {
        $reqUri = $_SERVER['REQUEST_URI'];


        if (\str_starts_with($reqUri, 'http')) {
            $_SERVER['REQUEST_SCHEME'] = \strstr($reqUri, '://', true);
            $addr = \strstr($reqUri, '://');
            $addr = \ltrim($addr ?: $reqUri, ':/');
            $host = \strstr($addr, '/', true);
            $_SERVER['REQUEST_URI'] = \strstr($addr, '/');
            $_SERVER['HTTP_HOST'] = $host ?: $_SERVER['HTTP_HOST'];
        }


        \str_starts_with($_SERVER['REQUEST_URI'], '/') or $_SERVER['REQUEST_URI'] = '/' . $_SERVER['REQUEST_URI'];
        $_SERVER['DOCUMENT_URI'] = \strstr($_SERVER['REQUEST_URI'], '?', true) ?: $_SERVER['REQUEST_URI'];
        $uri = \strstr($_SERVER['REQUEST_URI'], '?');
        $uri = \ltrim($uri ?: '', '?');
        $uri = $uri ? '?' . $uri : '';
        $_SERVER['QUERY_STRING'] = $uri;
        if (empty($_SERVER['SERVER_PORT'])) {
            $_SERVER['SERVER_PORT'] = \ltrim((string)\strstr($_SERVER['HTTP_HOST'], ':'), ':') ?: null;
        }
        $_SERVER['SERVER_PORT'] = (int)$_SERVER['SERVER_PORT'];
        if ((!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') ||
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === 443)) {
            $_SERVER['REQUEST_SCHEME'] = 'https';
            $_SERVER['HTTPS'] = 'on';
        } else {
            $_SERVER['REQUEST_SCHEME'] = 'http';
            $_SERVER['HTTPS'] = 'off';
        }
        $_SERVER['REQUEST_METHOD'] = \strtoupper($_SERVER['REQUEST_METHOD']);

        $_SERVER['REMOTE_ADDR'] = \strip_tags((string)($_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null));

        isset($_SERVER["SERVER_PROTOCOL"]) or $_SERVER["SERVER_PROTOCOL"] = 'HTTP/1.1';
    }

    protected function convertHost(string $host): string
    {
        if (\str_starts_with('xn--', $host)) {
            if (!SystemSettings::getRealPath('@library/idnaconv')) {
                throw new \InvalidArgumentException("To convert a domain $host, install the phphleb/idnaconv library");
            }
            $this->hostConvertor or $this->hostConvertor = new IdnaConvert();
            $host = (string)$this->hostConvertor->decode($_SERVER['HTTP_HOST']);
            $this->mode === self::ASYNC_MODE or $this->hostConvertor = null;
        }

        return $host;
    }

    protected function scriptExitEmulation(AsyncExitException $e): void
    {
        $this->output($e->getMessage(), $e->getStatus(), $e->getHeaders());
    }

    protected function getPreviousErrorControl(\Throwable $e): bool
    {
        $pr = $e->getPrevious();
        while ($pr !== null) {
            if (\get_class($pr) === AsyncExitException::class) {
                $this->scriptExitEmulation($pr);
                return true;
            }
            $pr = $pr->getPrevious();
        }
        return false;
    }

    protected function scriptHttpError(HttpException $e): void
    {
        $this->output($e->getMessageContent(), $e->getHttpStatus());
    }

    protected function scriptErrorHandling(\Throwable $t): void
    {
        $this->getLogger()->error($t);

        if (DynamicParams::isDebug()) {
            $message = PHP_EOL . '<pre>ERROR: ' . $t . '</pre>' . PHP_EOL;
        } else {
            $message = '';
        }
        $this->output($message, 500);
    }

    private function initialParameters(): void
    {
        if ($this->publicDirectory !== null) {
            $this->publicDirectory = \rtrim($this->publicDirectory, '/\\');
            if (!\is_dir($this->publicDirectory)) {
                $error = 'Wrong path to the project\'s public directory. ' .
                    'Check that the path is correct and the HLEB_PUBLIC_DIR constant is set in index.php and the ./console file.';
                throw new \InvalidArgumentException($error);
            }
            if (!\defined('HLEB_PUBLIC_DIR')) {
                \define('HLEB_PUBLIC_DIR', $this->publicDirectory);
            }
        }
        require __DIR__ . '/Init/Review/basic.php';

        $this->globalDirectory = \rtrim($this->searchGlobalDirectory(), '/\\');
        $this->vendorDirectory = \rtrim($this->searchVendorDirectory(), '/\\');

        $this->config = $this->getConfig();
        if ($this->config['common']['config.debug'] ?? null) {
            \defined('HLEB_STRICT_UMASK') or @\umask(0000);
        }
        \error_reporting($this->config['common']['error.reporting'] ?? null);

        $this->loadBaseClasses(); // #1
        SystemSettings::init($this->mode);
        SystemSettings::setData($this->config);


        Autoloader::init($this->vendorDirectory, $this->globalDirectory, $this->mode !== self::CONSOLE_MODE);

        if (!\function_exists('Hleb\agentLoader')) {
            $this->loadOtherClasses(); // #4
            $this->setDefaultAutoloader(); // #3
            $this->loadRequiredClasses(); // #2


            \define('HLEB_CONTAINER_MOCK_ON', $this->config['common']['container.mock.allowed']);
        }

        (new Functions())->create();
    }

    private function loadBaseClasses(): void
    {
        if (self::$loadResources) {
            return;
        }
        $dir = $this->vendorDirectory . '/phphleb/framework/';
        foreach (
            [BaseSingleton::class => 'Main/Insert/BaseSingleton.php',
                RollbackInterface::class => 'Base/RollbackInterface.php',
                SystemSettings::class => 'Constructor/Data/SystemSettings.php',
                BaseAsyncSingleton::class => 'Main/Insert/BaseAsyncSingleton.php',
                AsyncExitException::class => 'Constructor/Exceptions/Exit/AsyncExitException.php',
                Functions::class => 'Init/Functions.php',
                Autoloader::class => 'Init/Autoloader.php',
                DynamicParams::class => 'Constructor/Data/DynamicParams.php',
                HlebConnector::class => 'Init/Connectors/HlebConnector.php',
                AddressBar::class => 'Init/AddressBar.php',
                Response::class => 'HttpMethods/External/Response.php',
            ] as $name => $path) {
            \class_exists($name, false) or require $dir . $path;
        }
        self::$loadResources = true;
        if ($this->config['common']['debug']) {
            \class_exists(DebugAnalytics::class, false) or require $dir . 'Constructor/Data/DebugAnalytics.php';
        }
    }

    private function loadRequiredClasses(): void
    {
        if (!\function_exists('Hleb\reqLoadFunc')) {

            function reqLoadFunc(string $class): bool
            {
                $load = Autoloader::makeStatic($class);
                if ($load) {
                    require $load;
                }
                if (DynamicParams::isDebug()) {
                    DebugAnalytics::addData(DebugAnalytics::CLASSES_AUTOLOAD, [$class => $load]);
                }
                return (bool)$load;
            }

            \spl_autoload_register('Hleb\reqLoadFunc', true, true);
        }
    }

    private function loadOtherClasses(): void
    {
        if (!\function_exists('Hleb\otherLoadFunc') && $this->config['system']['classes.autoload']) {

            function otherLoadFunc(string $class): bool
            {
                $load = Autoloader::makeCustom($class);
                if ($load) {
                    require $load;

                    if (DynamicParams::isDebug()) {
                        DebugAnalytics::addData(DebugAnalytics::CLASSES_AUTOLOAD, [$class => $load]);
                    }
                }
                return (bool)$load;
            }
            \spl_autoload_register('Hleb\otherLoadFunc');
        }
    }

    private function verifiedUrlOrRedirect(SystemRequest $request): void
    {
        if (!$this->config['common']['config.debug'] && isset($this->config['common']['allowed.hosts'])) {
            $allowed = $this->config['common']['allowed.hosts'];
            $current = \explode(':', $request->getUri()->getHost())[0];
            if (!$allowed || !\is_array($allowed)) {
                $this->getLogger()->warning('common.allowed.hosts not set');
            } else if (!in_array($current, $allowed)) {
                $isValid = false;
                foreach ($allowed as $pattern) {
                    if (\str_starts_with($pattern, '/') && \preg_match($pattern, $current)) {
                        $isValid = true;
                        break;
                    }
                }
                if (!$isValid) {
                    async_exit('Invalid Host header', 400);
                }
            }
        }
        if ($request->getUri()->getPath() === '/') {
            return;
        }
        $urlValidator = $this->mode === self::ASYNC_MODE ? ($this->addressBar ??= new AddressBar()) : new AddressBar();
        $urlValidator->init(SystemSettings::getData(), $request);
        if ($urlValidator->check()->isUrlCompare()) {
            return;
        }
        async_exit('', 301, \array_merge(Response::getHeaders(), ['Location' => $urlValidator->getResultUrl()]));
    }

    private function headerOutput(): void
    {
        $res = Response::getInstance();
        if (!\headers_sent()) {
            foreach ($res->getHeaders() as $name => $header) {
                if (\is_array($header)) {
                    foreach($header as $h) {
                        \header("$name: $h");
                    }
                } else {
                    \header("$name: $header");
                }
            }
            if (\http_response_code() !== false && \http_response_code() !== 200) {
                return;
            }
            if ($res->getReason()) {
                $pr = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/' . $res->getVersion();
                \header($pr . ' ' . $res->getStatus() . ' ' . $res->getReason(), true, $res->getStatus());
            } else {
                \http_response_code($res->getStatus());
            }
        }
    }

    private function setErrorHandler(): void
    {


        if (!\defined('HLEB_CLI_MODE')) {
            \define('HLEB_CLI_MODE', $this->mode === self::CONSOLE_MODE);
        }
        if (!\defined('HLEB_LOAD_MODE')) {
            \define('HLEB_LOAD_MODE', $this->mode);
        }
        if (\function_exists('Hleb\core_user_log')) {
            return;
        }
        $logger = $this->logger;

        function core_user_log(int $errno, string $errstr, ?string $errfile = null, ?int $errline = null): bool
        {
            global $logger;

            $level = \error_reporting();
            if ($level >= 0 && ($level === 0 || !($level & $errno))) {
                return true;
            }
            \class_exists(ErrorLog::class, false) or require __DIR__ . '/Init/ErrorLog.php';

            ErrorLog::setLogger($logger);

            return ErrorLog::execute($errno, $errstr, $errfile, $errline);
        }

        \set_error_handler('Hleb\core_user_log');

        function core_bootstrap_shutdown(): void
        {
            if ($e = \error_get_last() and $e['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR)) {
                core_user_log(E_ERROR, $e['message'] ?? '', $e['file'] ?? null, $e['line'] ?? null);
            }
        }

        function core_bootstrap_log_finished(): void
        {
            if (\class_exists(FileLogger::class, false)) {
                FileLogger::finished();
            }
        }

        if ($this->mode !== self::ASYNC_MODE) {
            \register_shutdown_function('Hleb\core_bootstrap_shutdown');
        }
        \register_shutdown_function('Hleb\core_bootstrap_log_finished');
    }

    protected function logsPostProcessing(): void
    {
        if (\class_exists(FileLogger::class, false)) {
            FileLogger::finished();
        }
    }
}
