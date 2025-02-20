<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Deployer;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\DynamicStateException;
use Hleb\Init\Connectors\PhphlebConnector;
use Hleb\Main\Console\Commands\RouteCacheUpdater;

#[Accessible]
final class LibraryDeployer
{
    final public const HELP_PREFIX = '';

    private const All_ARGS = ['add', 'remove', '--config-path=', '-p=', '--no-interaction', '-n',  '--help', '--quiet'];

    private const REQUIRED_ARGS = ['add', 'remove'];

    private int $code = 0;

    public function getCode(): int
    {
        return $this->code;
    }

    public function run(array $args): false|string
    {
        $command = \array_shift($args);
        $action = $args ? \array_shift($args) : null;
        $quiet = \in_array('--quiet', $args);
        if (!in_array($action, self::REQUIRED_ARGS)) {
            return false;
        }
        if (!SystemSettings::getRealPath('@vendor/phphleb/updater')) {
            throw new DynamicStateException('`phphleb/updater` library not installed.');
        }
        $path = null;
        foreach ($args as $arg) {
            if (\str_starts_with($arg, '--config-path=') || \str_starts_with($arg, '-p=')) {
                $path = \explode('=', $arg)[1] ?? null;
            } else if (!\in_array($arg, self::All_ARGS)){
                throw new DynamicStateException('Invalid command argument: ' . $arg);
            }
        }
        $helper = new LibDeployerCreator();
        $deployer = $helper->createDeployer($command, $this->getConfig($command, $path));
        if ($deployer) {
            $help = $deployer->help();
            if ($action === '--help') {
                return $help ?: '[HELP] Library component in a project based on the HLEB framework';
            }
            if (\in_array('--no-interaction', $args) || \in_array('-n', $args)) {
                $deployer->noInteraction();
            }
            if ($quiet) {
                $deployer->quiet();
            }
            $resources = $deployer->classmap();
            if ($resources) {
                PhphlebConnector::add($resources);
            }
            if ($action === 'add') {
                $this->code = $deployer->add();
                $this->clearRouteCache();
                if ($quiet) {
                    return '';
                }
                return PHP_EOL . ($this->code ? $this->getErrorAdd($command) : $this->getSuccessAdd($command));
            }
            if ($action === 'remove') {
                $this->code = $deployer->remove();
                $this->clearRouteCache();
                if ($quiet) {
                    return '';
                }
                return PHP_EOL . ($this->code ? $this->getErrorRemove($command) : $this->getSuccessRemove($command));
            }
        }
        throw new DynamicStateException('Failed to execute command.');
    }

    private function searchLibrary(string $command): false|string
    {
        $command = \trim($command, '\\/');

        return SystemSettings::getRealPath('@vendor/' . $command);
    }

    private function getConfig(string $command, ?string $path = null): array
    {
        $file = $this->searchLibrary($command) . '/updater.json';
        if (\file_exists($file)) {
            $config = $this->createConfig($file);
            if ($path !== null) {
                if (!\str_ends_with($path, '.json')) {
                    throw new DynamicStateException('Wrong configuration file path.');
                }
                $config = SystemSettings::getPath('global') . DIRECTORY_SEPARATOR . \ltrim($path, '/\\');
                if (\file_exists($config)) {
                    return $this->createConfig($config);
                }
                throw new DynamicStateException('Configuration file not found.');
            }
            return $config;
        }
        return [];
    }

    private function createConfig(string $file): array
    {
        try {
            return (array)\json_decode(\file_get_contents($file), true, 10, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            throw new DynamicStateException((string)$e);
        }
    }

    private function getSuccessAdd(string $lib): string
    {
        return "Deployment of the $lib library completed successfully." . PHP_EOL . 'Don\'t forget to clear the app\'s cache.';
    }

    private function getErrorAdd(string $lib): string
    {
        return "Deployment of the $lib library completed with ERRORS.";
    }

    private function getSuccessRemove(string $lib): string
    {
        return "$lib rollback completed successfully!" . PHP_EOL . 'Don\'t forget to clear the app\'s cache.';
    }

    private function getErrorRemove(string $lib): string
    {
        return "$lib rollback completed with ERRORS.";
    }

    private function clearRouteCache(): void
    {
        (new RouteCacheUpdater())->run();
    }
}
