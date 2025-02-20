<?php


namespace Hleb\Main\Console;

use Hleb\Main\Console\Extreme\{ExtremeDataTransfer, ExtremeIdentifier, ExtremeRegister, ExtremeTerminal};
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Static\Request;
use Hleb\Static\Settings;

#[Accessible]
class WebConsole implements RollbackInterface
{
    private const GET_COMMANDS = ['php console --help', 'php console --list'];

    private readonly array $params;

    private static bool $used = false;

    private array $args = [];

    private bool $isAsync = false;

    public function __construct(?array $params = null)
    {
        $this->params = $params ?? $_GET ?: $_POST ?: $_REQUEST ?: [];
    }

    public static function isUsed(): bool
    {
        return self::$used;
    }

    public function load(): bool
    {
        self::$used = true;
        $this->isAsync = Settings::isAsync();

        $params = $this->params;
        $method = $_SERVER['REQUEST_METHOD'] ?? Request::getMethod();
        if (!$this->isAsync) {
            \session_id() or \session_start();
        }
        if (!$this->register() || !in_array($method, ['GET', 'POST'])) {
            return (new ExtremeRegister())->run();
        }
        $transfer = new ExtremeDataTransfer();
        if ($method === 'POST') {
            if (empty($params)) {
                (new ExtremeIdentifier())->exit();
            }
        }
        if ($method === 'GET' && !empty($params['command'])) {
            if (!in_array($params['command'], self::GET_COMMANDS)) {


                $params = [];
            }
        }
        $transfer->run($params);
        $this->args = $transfer->convertCommand();

        return (new ExtremeTerminal($transfer->singleGetCommand()))->get();
    }

    public function addFooter(string|bool|int $code): string
    {
        $code = \is_string($code) ? \htmlspecialchars($code, ENT_NOQUOTES) : '';

        return $code . '</pre>';
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    private function register(): bool
    {
        $checker = new ExtremeIdentifier($this->params);
        if ($checker->advance()) {
            return true;
        }
        $_POST = [];
        if ($checker->verification()) {
            $this->isAsync or \session_commit();
            return true;
        }
        return false;
    }

    public static function rollback(): void
    {
        self::$used = false;
    }
}
