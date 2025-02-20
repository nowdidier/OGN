<?php


namespace Hleb\Init;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Constructor\Data\MainLogLevel;
use Hleb\Init\Connectors\HlebConnector;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Main\Insert\BaseSingleton;
use Hleb\Main\Insert\OpenInstanceSingleton;
use Hleb\Main\Logger\BaseLogger;
use Hleb\Main\Logger\FileLogger;
use Hleb\Main\Logger\Log;
use Hleb\Main\Logger\LoggerInterface;
use Hleb\Main\Logger\LogLevel;
use Hleb\Main\Logger\NullLogger;
use Hleb\Main\Logger\StreamLogger;
use Hleb\Reference\LogInterface;
use Hleb\Static\Script;

final class ErrorLog
{

    protected static ?LoggerInterface $logger = null;

    private static array $config = [];

    private static array $notices = [];

    public static function setLogger(?LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function getLogger(): ?LoggerInterface
    {
        return self::$logger;
    }

    public static function handle(\Throwable $t): void
    {
        self::throw(self::make($t));
    }

    public static function log(\Throwable $throwable): void
    {
        self::make($throwable);
    }

    public static function execute(int $errno, string $errstr, ?string $errfile = null, ?int $errline = null): bool
    {
        try {
            self::loadBaseClasses();

            $params = [];
            if ($errfile) {
                $params['file'] = $errfile;
            }
            if ($errline) {
                $params['line'] = $errline;
            }
            $params['request-id'] = DynamicParams::getDynamicRequestId();
            $log = self::$logger ?? Log::instance();

            $debug = DynamicParams::isDebug();
            switch ($errno) {
                case E_CORE_ERROR:
                    self::outputNotice();
                    $log->critical($errstr, $params);
                    \async_exit($debug ? (SystemSettings::isCli() ?  '' : self::format($errstr)) : '', 500);
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                case E_PARSE:
                case E_COMPILE_ERROR:
                case E_RECOVERABLE_ERROR:
                    self::outputNotice();
                    $log->error($errstr, $params);
                    \async_exit($debug ? (SystemSettings::isCli() ?  '' : self::format($errstr)) : '', 500);
                    break;
                case E_USER_WARNING:
                case E_WARNING:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                    self::outputNotice();
                    $log->warning($errstr, $params);
                    $debug and print self::format( "Warning: $errstr in $errfile:$errline");
                    break;
                case E_USER_NOTICE:
                case E_NOTICE:

                case E_DEPRECATED:
                case E_USER_DEPRECATED:
                    $log->notice($errstr, $params);
                    $debug and self::$notices[] = self::format("Notice: $errstr in $errfile:$errline");
                    break;
                default:
                    self::outputNotice();
                    $log->error($errstr, $params);
                    \async_exit($debug ? (SystemSettings::isCli() ?  '' : self::format($errstr)) : '', 500);
                    break;
            }
            self::$config and SystemSettings::setData(self::$config);
        } catch (\Throwable $t) {
            \error_log((string)$t);
            self::$config = [];
            return false;
        }

        return true;
    }

    public static function rollback(): void
    {
        self::$config = [];
        self::$notices = [];
    }

    private static function make(\Throwable $t, int $error = E_USER_WARNING): \Throwable
    {
        try {
            self::execute($error, $t->getMessage() . ' ' . $t->getTraceAsString(), $t->getFile(), $t->getLine());
        } catch (\Throwable) {
        }
        return $t;
    }

    private static function throw($t): void
    {
        throw $t;
    }

    private static function loadBaseClasses(): void
    {
        try {
            $dir = \dirname(__DIR__);
            if (!\interface_exists(RollbackInterface::class, false)) {
                require_once $dir . '/Base/RollbackInterface.php';
            }
            foreach ([
                HlebConnector::class => '/Init/Connectors/HlebConnector.php',
                BaseAsyncSingleton::class => '/Main/Insert/BaseAsyncSingleton.php',
                DynamicParams::class => '/Constructor/Data/DynamicParams.php',
                ] as $name => $path) {
                if (!\class_exists($name, false)) {
                    require_once $dir . $path;
                }
            }
            foreach (HlebConnector::$exceptionMap as $excClass => $excName) {
                if (!\class_exists($dir . DIRECTORY_SEPARATOR . $excClass, false)) {
                    require_once $dir . DIRECTORY_SEPARATOR . $excName;
                }
            }
            $map = array_merge(HlebConnector::$map, HlebConnector::$formattedMap);
            $load = static function(array $classes) use ($map, $dir) {
                foreach ($classes as $class) {
                    if (!\class_exists($class, false)) {
                        require_once $dir . $map[$class];
                    }
                }
            };

            $beforeSettingsClasses = [
                BaseSingleton::class,
                OpenInstanceSingleton::class,
                LogLevel::class,
                SystemSettings::class,
                DynamicParams::class,
            ];
            $load($beforeSettingsClasses);

            self::loadSettings();

            $afterSettingsClasses = [
                LoggerInterface::class,
                LogInterface::class,
                \Hleb\Reference\Interface\Log::class,
                MainLogLevel::class,
                Script::class,
                Log::class,
                BaseLogger::class,
                NullLogger::class,
                FileLogger::class,
                StreamLogger::class,
                \Functions::class,
            ];
            $load($afterSettingsClasses);

            (new \Functions())->create();
        } catch (\Throwable $t) {
            \error_log((string)$t);
        }
    }

    private static function loadSettings(): void
    {
        if (!\function_exists('get_env')) {
            require __DIR__ . '/../Init/Review/basic.php';
        }
        self::$config = SystemSettings::getData();
        SystemSettings::init(HLEB_LOAD_MODE);
        $config = self::getMinConfig();
        $common = $config['common'];
        isset($common['timezone']) && \date_default_timezone_set($common['timezone']);
        $debug = ($common['debug'] ?? '0') ? '1' : '0';
        \ini_set('display_errors', $debug);
        LogLevel::setDefaultMaxLogLevel($common[HLEB_CLI_MODE ? 'max.cli.log.level' : 'max.log.level']);
        SystemSettings::setData($config);
        DynamicParams::setArgv($GLOBALS['argv'] ?? []);
    }

    private static function getMinConfig(): array
    {
        $dir = \defined('HLEB_GLOBAL_DIR') ? HLEB_GLOBAL_DIR : \dirname(__DIR__, 4);
        $c = (static function () use ($dir): array {
            try {
                if (\file_exists($directory = $dir . '/config/common.php')) {
                    return include $directory;
                }
            } catch (\Throwable) {
            }
            return [];
        })();
        !\is_bool($c['debug'] ?? null) and $c['debug'] = false;
        isset($c['log.enabled']) or $c['log.enabled'] = true;
        isset($c['max.log.level']) or $c['max.log.level'] = 'info';
        isset($c['max.cli.log.level']) or $c['max.log.level'] = 'info';
        isset($c['log.level.in-cli']) or $c['log.level.in-cli'] = false;
        isset($c['log.stream']) or $c['log.stream'] = false;
        isset($c['log.format']) or $c['log.format'] = 'row';
        return [
            'path' => [
                'global' => $dir,
                'storage' => $dir . '/storage',
                'public' => \defined("HLEB_PUBLIC_DIR") ? HLEB_PUBLIC_DIR : $dir . '/public',
            ],
            'common' => $c,
        ];
    }

    private static function outputNotice(): void
    {
        if (self::$notices) {
            print \implode(PHP_EOL, self::$notices);
            self::$notices = [];
        }
    }

    private static function format(string $message): string
    {
        if (SystemSettings::isCli()) {
            return $message . PHP_EOL . PHP_EOL;
        }

        return PHP_EOL . "<pre>$message</pre>" . PHP_EOL;
    }
}
