<?php


namespace Hleb\Main\Logger;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Cache\WebCron;
use Hleb\Database\SystemDB;
use Hleb\Static\Settings;
use Hleb\Static\System;
use RuntimeException;

#[Accessible] #[AvailableAsParent]
class FileLogger extends BaseLogger implements LoggerInterface
{
    protected const CACHE_LOGS_NUM = 100;

    private static ?string $requestId = null;

    private static bool $checkSize = false;

    private static array $memCache = [];

    public function __construct(
        readonly private string $storageDir,
        readonly private string $host,
        readonly private bool   $sortByDomain,
        readonly private bool   $isConsoleMode = false,
        bool   $isDebug = false,
    )
    {
        $this->isDebug = $isDebug;
    }

    public static function finished(): void
    {
        foreach(self::$memCache as $file => $logs) {
            self::saveText($file, \implode($logs));
        }
        self::$memCache = [];
    }

    #[\Override]
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('emergency', $message, $context), 'emergency');
    }

    #[\Override]
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('alert', $message, $context), 'alert');
    }

    #[\Override]
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('critical', $message, $context), 'critical');
    }

    #[\Override]
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('error', $message, $context), 'error');
    }

    #[\Override]
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('warning', $message, $context), 'warning');
    }

    #[\Override]
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('notice', $message, $context), 'notice');
    }

    #[\Override]
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('info', $message, $context), 'info');
    }

    #[\Override]
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog('debug', $message, $context), 'debug');
    }

    #[\Override]
    public function log(mixed $level, string|\Stringable $message, array $context = []): void
    {
        $this->saveFile($this->createLog($level, $message, $context), $level);
    }

    protected function delayedSave(?string $level, string $file, string $row): void
    {
        self::$memCache[$file][] = $row;

        if (\in_array($level, ['emergency', 'alert', 'critical', 'error']) ||
            \count(self::$memCache) >= self::CACHE_LOGS_NUM
        ) {
            self::finished();
        }
    }

    private function saveFile(string $row, ?string $level = null): void
    {
        $this->init();
        $I = DIRECTORY_SEPARATOR;
        $dir = $this->storageDir . $I . 'logs';
        try {
            $maxSize = Settings::getParam('common', 'max.log.size');
        } catch (\Throwable) {
            $maxSize = 0;
        }
        if ($maxSize > 0) {


            if (!self::$checkSize) {
                $this->clear($dir);
                self::$checkSize = true;
            }
        }
        $dbPrefix = $level === LogLevel::STATE && \str_contains($row, SystemDB::DB_PREFIX) ? '.db' : '';
        if (!\file_exists($dir)) {
            try {
                \set_error_handler(function ($_errno, $errstr) {
                    throw new RuntimeException($errstr);
                });
                \mkdir($dir, 0775, true);
            } catch (RuntimeException) {
            } finally {
                \restore_error_handler();
            }
        }
        if ($this->isConsoleMode) {
            $file = $dir . $I . \date('Y_m_d') . $dbPrefix . '.system.log';
            \file_put_contents($file, $row . PHP_EOL, FILE_APPEND|LOCK_EX);
            @\chmod($file, 0664);
            return;
        }
        $prefix = $this->sortByDomain ?
            (\str_replace(['\\', '//', '@', '<', '>'], '',
                \str_replace('127.0.0.1', 'localhost',
                    \str_replace('.', '_',
                        \explode(':', $this->host)[0]
                    )
                )
            ) ?: 'handler') : 'project';

        $file = $dir . $I . \date('Y_m_d_') . $prefix . $dbPrefix . '.log';
        $this->delayedSave($level, $file, $row . PHP_EOL);
    }

    private function clear(string $dir): void
    {
        WebCron::offer('hl_file_logger_cache', function() use ($dir) {
            (new LogCleaner())->run($dir, 'Y_m_d');
        }, 3600);
    }

    private static function saveText(string $file, string $text): void
    {
        @\file_put_contents($file, $text, FILE_APPEND);
        @\chmod($file, 0664);
    }

    private function init(): void
    {
        try {


            $requestId = System::getRequestId();
        } catch (\Throwable) {
            self::$requestId = \sha1(\rand());
            self::$checkSize = true;
            return;
        }

        if (self::$requestId !== $requestId) {
            self::$requestId = $requestId;
            self::$checkSize = false;
        }
    }
}
