<?php

declare(strict_types=1);

namespace Hleb\Main\Console;

use Hleb\Constructor\Actions\ClearCacheAction;
use Hleb\Constructor\Data\Key;
use Hleb\CoreErrorException;
use Hleb\CoreException;
use Hleb\Main\Console\Commands\CliLogLevel;
use Hleb\Main\Console\Commands\ConfigInfo;
use Hleb\Main\Console\Commands\CustomTask;
use Hleb\Main\Console\Commands\Deployer\LibDeployerFinder;
use Hleb\Main\Console\Commands\Deployer\LibraryDeployer;
use Hleb\Main\Console\Commands\Features\AutoloaderSupport\AutoloaderSupport;
use Hleb\Main\Console\Commands\Features\CodeCleanup\ClearingComments;
use Hleb\Main\Console\Commands\Features\CommandDetails\AllCommands;
use Hleb\Main\Console\Commands\Features\CommandDetails\CommandArgument;
use Hleb\Main\Console\Commands\Features\ExecutionSpeed\ExecutionSpeed;
use Hleb\Main\Console\Commands\Features\FeatureInterface;
use Hleb\Main\Console\Commands\Features\FlatKegling\FlatKeglingGame;
use Hleb\Main\Console\Commands\Features\OriginCommand\OriginCommandReturn;
use Hleb\Main\Console\Commands\Features\ReverseMode\ReverseHlCheckMode;
use Hleb\Main\Console\Commands\Features\ReverseMode\ReverseStrictMode;
use Hleb\Main\Console\Commands\LatestLogs;
use Hleb\Main\Console\Commands\LockProject;
use Hleb\Main\Console\Commands\RouteCacheUpdater;
use Hleb\Main\Console\Commands\RouteClearCache;
use Hleb\Main\Console\Commands\RouteInfo;
use Hleb\Main\Console\Commands\RouteList;
use Hleb\Main\Console\Commands\SearchRoute;
use Hleb\Main\Console\Commands\ShortList;
use Hleb\Main\Console\Commands\TemplateCreator;
use Hleb\Main\Console\Commands\TwigCacheUpdater;
use Hleb\Main\Console\Sections\ModuleCreator;
use Hleb\Static\Settings;

final class ConsoleHandler
{
    final public const DEFAULT_MESSAGE = 'Command not found. List of available commands --help or --list';

    private const DEFAULT_COMMANDS = [
        '--help', '-h',
        '--version', '-v',
        '--info', '-i',
        '--logs', '-lg',
        '--log-level', '-ll',
        '--routes', '-r',
        '--list', '-l',
        '--find-route', '-fr',
        '--route-info', '-ri',
        '--update-routes-cache', '--routes-upd', '-u',
        '--clear-routes-cache', '-cr',
        '--clear-cache', '-cc',
        '--lock-project',
        '--unlock-project',
        '--add',
        '--create',
        '--ping',
        '--plain-version', '-pv',
    ];

    private array $arguments;

    private readonly array $originConfig;

    private readonly ?string $firstArgument;

    private readonly ?string $secondArgument;

    private readonly ?string $thirdArgument;

    private readonly ?string $fourthArgument;

    private readonly ?string $fifthArgument;

    private readonly Colorizer $color;

    private int $code = 0;

    public function __construct(array $argv = [], array $config = [])
    {
        $this->arguments = \array_slice($argv, 1);
        $this->originConfig = $config;
        $this->firstArgument = $this->arguments[0] ?? null;
        $this->secondArgument = $this->arguments[1] ?? null;
        $this->thirdArgument = $this->arguments[2] ?? null;
        $this->fourthArgument = $this->arguments[3] ?? null;
        $this->fifthArgument = $this->arguments[4] ?? null;
        $this->color = new Colorizer();
    }

    public function run(): string|bool|int
    {
        $command = $this->firstArgument ?? '--help';

        $feature = $this->searchAndRunFeature($command);
        if ($feature !== false) {
            return $feature;
        }
        $library = $this->searchAndRunLibCommand($command);
        if ($library !== false) {
            return $library;
        }

        return match ($command) {
            '--help', '-h' => $this->getHelp(),
            '--version', '-v' => $this->getVersion(),
            '--info', '-i' => $this->getConfigInfo(),
            '--logs', '-lg' => $this->getLogs(),
            '--log-level', '-ll' => $this->updateLogLevel(),
            '--routes', '-r' => $this->getRoutes(),
            '--list', '-l' => $this->getTaskList(),
            '--find-route', '-fr' => $this->searchRoute(),
            '--route-info', '-ri' => $this->routeInfo(),
            '--update-routes-cache', '--routes-upd', '-u' => $this->updateRouteCache(),
            '--clear-routes-cache', '-cr' => $this->clearRouteCache(),
            '--clear-cache', '-cc' => $this->updateCache(),
            '--clear-cache--twig', '-cc-twig' => $this->updateTwigCache(),
            '--lock-project' => $this->lockProject(status: true),
            '--unlock-project' => $this->lockProject(status: false),
            '--add' => $this->addTemplate(),
            '--create' => $this->createSection(),
            '--ping' => $this->getPong(),
            '--generate-key' => $this->getGenerateKey(),
            '--plain-version', '-pv' => $this->getPlainVersion(),
            default => $this->searchAndRunTask(),
        };
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getVersion(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays the formatted output of the current version of the framework.' . PHP_EOL;
        }
        $frameworkVersion = \defined('HLEB_CORE_VERSION') ? HLEB_CORE_VERSION : '2.x.x';
        return PHP_EOL . "  " . $this->color::yellow("HLEB2 Framework ") . " " . $this->color::green("v" . $frameworkVersion) .
            "  (c)2019 - " . \date("Y") . " Foma Tuturov" . PHP_EOL . PHP_EOL;
    }

    private function getConfigInfo(): string|false
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays the settings from the `config/common.php` file.' . PHP_EOL;
        }
        return (new ConfigInfo())->run($this->originConfig, $this->secondArgument);
    }

    public function updateRouteCache(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays a message that the route cache was updated successfully, or an error message.' . PHP_EOL;
        }
        $updater = new RouteCacheUpdater();
        $result = $updater->run();
        $this->code = $updater->getCode();

        return $result;
    }

    public function clearRouteCache(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays a message that the route cache was cleared successfully, or an error message.' . PHP_EOL;
        }
        if (!Settings::getParam('common', 'routes.auto-update')) {
            $info = 'The cache can only be updated due to the `routes.auto-update` option being disabled.' . PHP_EOL;
            return $info . $this->updateRouteCache();
        }
        $updater = new RouteClearCache();
        $result = $updater->run();
        $this->code = $updater->getCode();

        return $result;
    }

    public function updateCache(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command clears the framework cache for cacheable templates and using the default cache.' . PHP_EOL;
        }
        (new ClearCacheAction())->run();

        return 'Successfully cleared framework cache.' . PHP_EOL;
    }

    public function updateTwigCache(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays a message that the Twig cache was clear successfully, or an error message.' . PHP_EOL;
        }
        if (!$this->searchTwig()) {
            $this->code = 1;
            return 'Twig template engine not installed! Run: composer require "twig/twig:^3.0"' . PHP_EOL;
        }

        $updater = new TwigCacheUpdater();
        $result = $updater->run();
        $this->code = $updater->getCode();

        return $result;
    }

    private function getHelp(): string
    {
        if ($this->secondArgument === '--help') {
            return '[HELP] This command displays a list of available basic commands.' . PHP_EOL;
        }
        return ' --version or -v             (displays the version of the framework)' . PHP_EOL .
            ' --info or -i [name]  (displays the value(s) of the common settings)' . PHP_EOL .
            ' --help or -h           (displays a list of default console actions)' . PHP_EOL .
            ' --ping             (service health check, returns a constant value)' . PHP_EOL .
            ' --logs or -lg      (prints multiple trailing lines from a log file)' . PHP_EOL .
            ' --list or -l                      (list of native console commands)' . PHP_EOL .
            ' --routes or -r                             (forms a list of routes)' . PHP_EOL .
            ' --find-route (or -fr) <url> [method] [domain] (route search by url)' . PHP_EOL .
            ' --route-info (or -ri) <url> [method] [domain]   (route info by url)' . PHP_EOL .
            ' --clear-routes-cache or -cr                 (clearing route cache)' . PHP_EOL .
            ' --update-routes-cache or --routes-upd or -u    (update route cache)' . PHP_EOL .
            ' --clear-cache or -cc                        (clear framework cache)' . PHP_EOL .
            ' --add <task|controller|middleware|model> <name> [desc]   (template)' . PHP_EOL .
            ' --create module <name>                           (section creating)' . PHP_EOL .
            ($this->searchTwig() ? ' --clear-cache--twig or -cc-twig    (clear cache for Twig templates)' . PHP_EOL : '') .
            PHP_EOL . ' <command> --help        (get help about the purpose of the command)' . PHP_EOL;
    }

    private function searchTwig(): bool
    {
        return \class_exists('Twig\Environment');
    }

    private function getLogs(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays the last three lines of the last five log files.' . PHP_EOL;
        }
        return (new LatestLogs())->run($this->secondArgument, $this->thirdArgument);
    }

    public function getRoutes(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays a list of routes sorted by HTTP methods.' . PHP_EOL;
        }
        return (new RouteList())->run();
    }

    private function searchRoute(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays the result of the route search.' . PHP_EOL .
                'Examples:' . PHP_EOL .
                ' php console -fr /page' . PHP_EOL .
                ' php console --find-route /page get' . PHP_EOL .
                ' php console -fr https://site.ru/api POST' . PHP_EOL .
                ' php console --find-route /api post site.ru' . PHP_EOL . ' ...' . PHP_EOL;
        }
        $task = new SearchRoute();
        $result = $task->run(
            $this->secondArgument,
            $this->thirdArgument,
            $this->fourthArgument
        );
        $this->code = $task->getCode();

        return $result;
    }

    private function routeInfo(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays route information by URL.' . PHP_EOL .
                'Examples:' . PHP_EOL .
                ' php console -ri /page' . PHP_EOL .
                ' php console --route-info /page get' . PHP_EOL .
                ' php console -ri https://site.ru/api POST' . PHP_EOL .
                ' php console --route-info /api post site.ru' . PHP_EOL . ' ...' . PHP_EOL;
        }
        $task = new RouteInfo();
        $result = $task->run(
            $this->secondArgument,
            $this->thirdArgument,
            $this->fourthArgument,
            $this->fifthArgument,
        );
        $this->code = $task->getCode();

        return $result;
    }

    private function lockProject(bool $status): string
    {
        if (\end($this->arguments) === '--help') {
            if ($status) {
                return '[HELP] With this command, you can LOCK a project. Beware, the site is unavailable during blocking!' . PHP_EOL;
            }
            return '[HELP] With this command, you can unlock a project.' . PHP_EOL;
        }
        return (new LockProject())->run($status);
    }

    private function getTaskList(): string|false
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command displays a list of custom console commands.' . PHP_EOL;
        }
        return (new ShortList())->run();
    }

    private function updateLogLevel(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] This command allows you to set the logging level through the console. ' . PHP_EOL .
                '* Calling with no arguments returns the current global logging level.' . PHP_EOL .
                '* You can set the level by passing a name, or disable it by passing `default`.' . PHP_EOL .
                '  php console --log-level (global level display)' . PHP_EOL .
                '  php console --log-level error (level change)' . PHP_EOL .
                '  php console --log-level default (rollback changes)' . PHP_EOL;
        }

        return (new CliLogLevel())->run($this->secondArgument);
    }

    private function searchAndRunTask(): false|string
    {
        try {
            $task = new CustomTask();
            $result = $task->searchAndRun($this->arguments);
            $this->code = $task->getCode();
        } catch (\AsyncExitException $e) {


            return $e->getMessage();
        }
       return $result;
    }

    private function addTemplate(): false|string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] Adds the specified template file to the project.' . PHP_EOL .

                '* task section/task-name [Description] ' .
                '- create a new command from its name:' .
                ' App\\Commands\\Section\\TaskName' . PHP_EOL .

                '* controller Section/ClassName [Description] ' .
                '- create a new Controller from a class name:' .
                ' App\\Controllers\\Section\\ClassName' . PHP_EOL .

                '* middleware Section/ClassName [Description] ' .
                '- create a new Middleware from a class name:' .
                ' App\\Middlewares\\Section\\ClassName' . PHP_EOL .

                '* model Section/ClassName [Description] ' .
                '- create a new Model from a class name:' .
                ' App\\Models\\Section\\ClassName' . PHP_EOL;
        }
        return (new TemplateCreator())->run($this->secondArgument, $this->thirdArgument, (string)$this->fourthArgument);
    }

    private function searchAndRunFeature(string $command): false|string
    {


        if ($command === 'execution-speed-feature') {
            return $this->runFeature(new ExecutionSpeed());
        }


        if ($command === 'flat-kegling-feature') {
            return $this->runFeature(new FlatKeglingGame());
        }


        if ($command === 'define-argument-feature') {
            return $this->runFeature(new OriginCommandReturn());
        }


        if ($command === 'reverse-strict-mode-feature') {
            return $this->runFeature(new ReverseStrictMode());
        }


        if ($command === 'reverse-hl-check-feature') {
            return $this->runFeature(new ReverseHlCheckMode());
        }


        if ($command === 'autoloader-support-feature') {
            return $this->runFeature(new AutoloaderSupport());
        }


        if ($command === 'clearing-comment-feature') {
            return $this->runFeature(new ClearingComments());
        }


        if ($command === 'command-list-feature') {
            return $this->runFeature(new AllCommands(self::DEFAULT_COMMANDS));
        }


        if ($command === 'command-arguments-feature') {
            return $this->runFeature(new CommandArgument(self::DEFAULT_COMMANDS));
        }
        return false;
    }

    private function runFeature(FeatureInterface $feature): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] ' . ($feature::getDescription()) . PHP_EOL;
        }
        $result = $feature->run($this->arguments);
        $this->code = $feature->getCode();

        return $result;
    }

    private function searchAndRunLibCommand(string $command): false|string
    {
        if (!\str_contains($command, '/')) {
            return false;
        }
        if (!(new LibDeployerFinder())->isExists($command)) {
            return false;
        }
        if (\end($this->arguments) === '--help') {
            return "[HELP] Execute commands attached to the $command component." . PHP_EOL;
        }
        $handler = new LibraryDeployer();
        $result = $handler->run($this->arguments);
        $this->code = $handler->getCode();
        if (\is_string($result)) {
            if ($result !== '') {
                $result .= PHP_EOL;
            }
            return $result;
        }
        return false;
    }

    private function createSection(): false|string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] Creating a project section with nested directories and files.' . PHP_EOL;
        }
        if ($this->secondArgument === 'module' && $this->thirdArgument) {
            return (new ModuleCreator())->run($this->thirdArgument);
        }
        return false;
    }

    private function getPong(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] Service health check. Returns PONG on success.' . PHP_EOL;
        }
        return "PONG";
    }

    private function getGenerateKey(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] Generating a project key if it is missing.' . PHP_EOL;
        }
        if (!Key::generateIfNotExists()) {
            throw new CoreErrorException('Error while creating the key.');
        }
        return 'The secret key has been generated.' . PHP_EOL;
    }

    private function getPlainVersion(): string
    {
        if (\end($this->arguments) === '--help') {
            return '[HELP] Returns the version number.' . PHP_EOL;
        }
        return HLEB_CORE_VERSION;
    }
}
