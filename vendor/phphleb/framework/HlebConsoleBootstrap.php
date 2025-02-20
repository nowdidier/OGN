<?php


declare(strict_types=1);

namespace Hleb;

use Hleb\Constructor\Data\{DynamicParams, SystemSettings};
use Hleb\Main\{Console\ConsoleHandler, Console\WebConsole, Logger\Log, Logger\LoggerInterface, Logger\LogLevel};
use Exception;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;

#[Accessible] #[AvailableAsParent]
class HlebConsoleBootstrap extends HlebBootstrap
{
    private int $code = 0;

    private ?array $argv = null;

    public function __construct(?string $publicPath = null, array $config = [], ?LoggerInterface $logger = null)
    {
        $this->mode = self::CONSOLE_MODE;

        \defined('HLEB_START') or \define('HLEB_START', \microtime(true));

        parent::__construct($publicPath, $config, $logger);
    }

    public function setArgv(array $argv): self
    {
        $this->argv = $argv;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    #[\Override]
    public function load(): int
    {
        $this->code = 0;

        try {
            $cli = $this->loadConsoleApp();

        } catch (\AsyncExitException $e) {


            $cli = $e->getMessage();
        } catch (CoreException $e) {
            echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
            $this->getLogger()->error($e);
            $this->code = 1;

            return $this->code;
        } catch (\Throwable $t) {


            $pr = $t->getPrevious();
            while ($pr !== null) {
                if (\get_class($pr) === \AsyncExitException::class) {
                    $cli = $pr->getMessage();
                    break;
                }
                $pr = $pr->getPrevious();
            }
            $pr or throw $t;
        }

        if (\is_string($cli)) {
            echo $cli;
        } else if (!$cli) {
            echo ConsoleHandler::DEFAULT_MESSAGE . PHP_EOL;
            $this->code = 1;
        }

        return $this->code;
    }

    private function loadConsoleApp(): string|bool
    {
        $this->logger and Log::setLogger($this->logger);
        $argv = $this->argv ?? $GLOBALS['argv'] ?? $_SERVER['argv'] ?? [];
        LogLevel::setDefaultMaxLogLevel(SystemSettings::getCommonValue('max.cli.log.level'));
        DynamicParams::setArgv($argv);
        \date_default_timezone_set($this->config['common']['timezone']);

        $webConsole = new WebConsole();
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            if (!$webConsole->load()) {
                return '';
            }
            $webArgs = $webConsole->getArgs();
        }
        $handler = (new ConsoleHandler($webArgs ?? $argv, $this->config));
        $result = $handler->run();
        $this->code = $handler->getCode();
        if ($result === false) {
            return false;
        }
        isset($webArgs) and $result = $webConsole->addFooter($result);

        return $result;
    }
}
