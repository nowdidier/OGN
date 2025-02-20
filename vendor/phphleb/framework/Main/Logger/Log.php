<?php

declare(strict_types=1);

namespace Hleb\Main\Logger;

use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Constructor\Data\MainLogLevel;
use Hleb\Init\ErrorLog;
use Hleb\InvalidLogLevelException;
use Hleb\Main\Insert\BaseSingleton;
use Throwable;

final class Log extends BaseSingleton implements LoggerInterface
{

    final public const B7E_NAME = 'backtrace-level';

    final public const ERROR_B7E = 1;

    final public const DB_B7E = 4;

    final public const LOGGER_B7E = 2;

    final public const STATIC_B7E = 3;

    final public const WRAPPER_B7E = 4;

    private const DEFAULT_B7E = ['line' => 0, 'file' => 0, 'function' => 1, 'class' => 1, 'type' => 1];

    private static ?LoggerInterface $logger = null;

    public static function instance(): Log
    {
        return self::getInstance();
    }

    #[\Override]
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('emergency')) {
            self::initLogger();
            self::$logger->emergency($this->convertMessage($message, $context), self::prepareContext('emergency', $context));
        }
    }

    #[\Override]
    public function alert(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('alert')) {
            self::initLogger();
            self::$logger->alert($this->convertMessage($message, $context), self::prepareContext('alert', $context));
        }
    }

    #[\Override]
    public function critical(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('critical')) {
            self::initLogger();
            self::$logger->critical($this->convertMessage($message, $context), self::prepareContext('critical', $context));
        }
    }

    #[\Override]
    public function error(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('error')) {
            self::initLogger();
            self::$logger->error($this->convertMessage($message, $context), self::prepareContext('error', $context));
        }
    }

    #[\Override]
    public function warning(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('warning')) {
            self::initLogger();
            self::$logger->warning($this->convertMessage($message, $context), self::prepareContext('warning', $context));
        }
    }

    #[\Override]
    public function notice(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('notice')) {
            self::initLogger();
            self::$logger->notice($this->convertMessage($message, $context), self::prepareContext('notice', $context));
        }
    }

    #[\Override]
    public function info(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('info')) {
            self::initLogger();
            self::$logger->info($this->convertMessage($message, $context), self::prepareContext('info', $context));
        }
    }

    #[\Override]
    public function debug(string|\Stringable $message, array $context = []): void
    {
        if (self::checkLevel('debug')) {
            self::initLogger();
            self::$logger->debug($this->convertMessage($message, $context), self::prepareContext('debug', $context));
        }
    }

    #[\Override]
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        if (!\in_array($level, LogLevel::REQUIRED, true)) {
            $list = \implode(', ', LogLevel::ALL);
            throw new InvalidLogLevelException("Specified logging level `$level` is not supported, use: " . $list);
        }
        if (self::checkLevel((string)$level)) {
            self::initLogger();
            self::$logger->log($level, $this->convertMessage($message, $context), self::prepareContext($level, $context));
        }
    }

    public static function setLogger(?LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function getLogger(): LoggerInterface
    {
        self::initLogger();

        return self::$logger;
    }

    private static function prepareContext(string $level, array $context = []): array
    {
        $cells = [];
        $context['is_queue'] = (int)\defined('HLEB_IS_QUEUE');
        try {
            $cells = ['request-id' => DynamicParams::getDynamicRequestId()];
            if (SystemSettings::isCli()) {
                $context['command'] = DynamicParams::getConsoleCommand();
                $context['is_console'] = 1;
            } else {
                $request = DynamicParams::getRequest();
                $context['domain'] = $request->getUri()->getHost();
                $context['url'] = $request->getUri()->getPath();
                $ip = $request->getUri()->getIp();
                if ($ip) {
                    $context['ip'] = $ip;
                }
                $context['http_method'] = $request->getMethod();
                $context['scheme'] = $request->getUri()->getScheme();
                $context['query'] = $request->getUri()->getQuery();
                $context['is_console'] = 0;

            }
        } catch (Throwable) {
            if (\defined('HLEB_CLI_MODE') && \constant('HLEB_CLI_MODE')) {
                $context['is_console'] = 1;
                $context['command'] = empty($_GLOBALS['argv']) ? '' : \implode(' ', $_GLOBALS['argv']);
            } else {
                $context['is_console'] = 0;
                $context['url'] = $_SERVER['DOCUMENT_URI'] ?? null;
                $context['domain'] = $_SERVER['HTTP_HOST'] ?? null;
                $context['http_method'] = $_SERVER['REQUEST_METHOD'] ?? null;
                $context['scheme'] = $_SERVER['REQUEST_SCHEME'] ?? null;
                $context['query'] = $_SERVER['QUERY_STRING'] ?? null;
            }
        }

        if (isset($context['file'], $context['line'])) {
            return $context;
        }

        $b7e = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 9);
        $searchList = \array_map(function($v) use ($context) {
            return $v + (!empty($context[self::B7E_NAME]) ? (int)$context[self::B7E_NAME] : self::ERROR_B7E);
        }, self::DEFAULT_B7E);
        if ($b7e && isset($b7e[max($searchList)])) {
            foreach ($searchList as $name => $num) {
                if (isset($b7e[$num][$name])) {
                    $cells[$name] = $b7e[$num][$name];
                }
            }
        }
        if (isset($cells['type'])) {
            $cells['method'] = $cells['type'];
        }
        $cells['type'] = $level;
        unset($context[self::B7E_NAME]);

        return \array_merge($context, $cells);
    }

    private static function checkLevel(string $level): bool
    {
        $default = LogLevel::getDefault();
        try {
            if (SystemSettings::getCommonValue('log.level.in-cli')) {
                $default = MainLogLevel::get();
            }
        } catch (\Throwable) {
        }
        $search = \array_search($level, LogLevel::REQUIRED);
        if ($search === false) {
            return false;
        }
        return $search <= \array_search($default, LogLevel::ALL);
    }

    private function convertMessage(string|\Stringable $message, array &$context): string
    {
        $message = (string)$message;
        $this->updateContext($context);
        if (\str_contains($message, '{')) {
            foreach ($context as $key => $value) {
                if (\str_contains($message, '{' . $key . '}')) {
                    $message = \str_replace('{' . $key . '}', (string)$value, $message);
                    unset($context[$key]);
                }
            }
        }
        return $message;
    }

    private function updateContext(array &$context): void
    {
        foreach ($context as &$c) {
            if (\is_resource($c)) {
                $c = \print_r($c, true);
            } else if (\is_object($c)) {
                $c = \method_exists($c, '__toString') ? (string)$c : '{"' . $c::class . '":' . \json_encode($c) . '}';
            }
        }
    }

    private static function initLogger(): void
    {
        try {
            if (self::$logger === null) {
                if (!SystemSettings::getLogOn()) {
                    self::setLogger(new NullLogger());
                }
                if (\class_exists(ErrorLog::class, false) &&
                    $logger = ErrorLog::getLogger()
                ) {
                    self::setLogger($logger);
                } else {
                    if (SystemSettings::getCommonValue('log.stream')) {
                        self::setLogger((new StreamLogger(
                            SystemSettings::getCommonValue('log.stream'),
                            DynamicParams::getHost() ?? 'local',
                            DynamicParams::isDebug(),
                        ))->setFormat(SystemSettings::getCommonValue('log.format')));
                    } else {
                        self::setLogger((new FileLogger(
                            SystemSettings::getRealPath('storage'),
                            DynamicParams::getHost() ?? '',
                            SystemSettings::getSortLog(),
                            SystemSettings::isCli(),
                            DynamicParams::isDebug(),
                        ))->setFormat(SystemSettings::getCommonValue('log.format')));
                    }
                }
            }
        } catch (Throwable $t) {


            \error_log((string)$t);
        }
    }
}
