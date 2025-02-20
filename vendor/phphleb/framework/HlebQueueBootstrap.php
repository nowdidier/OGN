<?php


declare(strict_types=1);

namespace Hleb;

use App\Bootstrap\ContainerFactory;
use ErrorException;
use Hleb\Base\RollbackInterface;
use Hleb\Base\Task;
use Hleb\Constructor\Data\DebugAnalytics;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Init\ErrorLog;
use Hleb\Main\Logger\Log;
use Hleb\Main\Logger\LoggerInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Main\Logger\LogLevel;
use Throwable;

#[Accessible] #[AvailableAsParent]
class HlebQueueBootstrap extends HlebBootstrap
{
    private static int $processNumber = 0;

    protected const SUPPORTED_MODES = [self::ASYNC_MODE, self::CONSOLE_MODE, self::STANDARD_MODE];

    protected mixed $result = null;

    protected bool $verbosity = true;

    public function __construct(
        ?string          $publicPath = null,
        array            $config = [],
        ?LoggerInterface $logger = null,
        int              $mode = self::STANDARD_MODE,
    ) {
        \defined('HLEB_IS_QUEUE') or \define('HLEB_IS_QUEUE', 'on');

        if (!\in_array($mode, self::SUPPORTED_MODES)) {
            throw new \ErrorException('Unsupported mode');
        }
        $this->mode = $mode;

        \defined('HLEB_START') or \define('HLEB_START', \microtime(true));


        try {
            parent::__construct($publicPath, $config, $logger);
        } catch (Throwable $t) {
            $this->errorLog($t);
            throw $t;
        }
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function setVerbosity(bool $value): void
    {
        $this->verbosity = $value;
    }

    #[\Override]
    public function load(?string $commandClass = null, array $arguments = []): int
    {
        self::$processNumber++;

        \date_default_timezone_set($this->config['common']['timezone']);

        if (!$commandClass) {
            throw new ErrorException('The command must be specified.');
        }
        if (!\is_a($commandClass, Task::class, true)) {
            throw new ErrorException('The command class must be inherited from ' . Task::class);
        }

        $status = true;

        try {
            $this->loadSettings();

            $command = new $commandClass();

            $status = $command->call($arguments, strictVerbosity: $this->verbosity);

            $this->result = $command->getResult();

        } catch (\AsyncExitException $e) {
            echo $e->getMessage();

        } catch (Throwable $t) {
            $this->logsPostProcessing();
            $this->errorLog($t);
            if ($this->mode !== self::ASYNC_MODE) {
                throw $t;
            }
            $status = false;

        } finally {
            $this->logsPostProcessing();
            if ($this->mode === self::ASYNC_MODE) {
                self::prepareAsyncRequestData($this->config, self::$processNumber);
            }
        }

        return (int)($status != false);
    }

    protected static function prepareAsyncRequestData(array $config, int $processNumber): void
    {


        if ($config['system']['async.clear.state'] ?? true) {
            foreach (\get_declared_classes() as $class) {
                \is_a($class, RollbackInterface::class, true) and $class::rollback();
            }
        }
        foreach ([ContainerFactory::class, Registrar::class, DebugAnalytics::class, ErrorLog::class] as $class) {
            \class_exists($class, false) and $class::rollback();
        }

        $rate = (int)get_env('HLEB_ASYNC_RE_CLEANING', get_constant('HLEB_ASYNC_RE_CLEANING', self::DEFAULT_RE_CLEANING));
        if ($rate >= 0 && ($rate === 0 || $processNumber % $rate == 0)) {
            \gc_collect_cycles();
            \gc_mem_caches();
        }
        \memory_reset_peak_usage();
    }

    public function errorLog(Throwable $e): void
    {


        try {
            \class_exists(ErrorLog::class, false) or require __DIR__ . '/Init/ErrorLog.php';
            ErrorLog::log($e);
        } catch (Throwable $t) {
            \error_log((string)$e);
            \error_log((string)$t);
        }
    }

    protected function loadSettings(): void
    {
        $startTime = \defined('HLEB_START') ? HLEB_START : \microtime(true);
        $this->config['system']['start.unixtime'] = $startTime;
        SystemSettings::setStartTime($startTime);
        $this->logger and Log::setLogger($this->logger);
        LogLevel::setDefaultMaxLogLevel(SystemSettings::getCommonValue('max.log.level'));
        \date_default_timezone_set($this->config['common']['timezone']);
        \ini_set('display_errors', $this->config['common']['debug'] ? '1' : '0');
    }
}
