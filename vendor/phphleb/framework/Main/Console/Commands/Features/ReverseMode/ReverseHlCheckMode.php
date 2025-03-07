<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\ReverseMode;

use FilesystemIterator;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Main\Console\Commands\Features\FeatureInterface;
use Hleb\Static\Path;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class ReverseHlCheckMode implements FeatureInterface
{
    private const DESCRIPTION = '(!) Reverse hl_check(...) mode in framework';

    private const ORIGIN_START = '/** @see hl_check() -';
    private const ORIGIN_END = '*/';

    private const REPLACE_START = '\hl_check(\'';
    private const REPLACE_END = '\', __FILE__, __LINE__); /*[0]*/';

    private string $path = '@framework';

    #[\Override]
    public function run(array $argv): string|false
    {
        if (!\in_array($argv[1] ?? null, ['--set', '--undo'])) {
            return '[HELP] The action --set or --undo must be specified.' . PHP_EOL;
        }
        if (!empty($argv[2])) {
            $this->path = $argv[2];
            if (!Path::getReal($this->path)) {
                throw new \RuntimeException('Wrong path to process!');
            }
        }
        if ($argv[1] === '--set') {
            $this->changeMode('setChecker');
        }
        if ($argv[1] === '--undo') {
            $this->changeMode('undoChecker');
        }

        return 'Mode change completed.' . PHP_EOL;
    }

    #[\Override]
    public static function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    #[\Override]
    public function getCode(): int
    {
        return 0;
    }

    private function setChecker(string $file): void
    {
        $rows = \file($file);
        $search = 0;
        foreach ($rows as &$row) {
            if (\str_contains($row, self::ORIGIN_START)) {
                $parts = \explode(self::ORIGIN_START, $row);
                if (\count($parts) !== 2) {
                    continue;
                }
                $endParts = \explode(self::ORIGIN_END, $parts[1]);
                if (\count($endParts) !== 2) {
                    continue;
                }
                $message = \trim($endParts[0]);
                $row = $parts[0] . self::REPLACE_START . $message . self::REPLACE_END . $endParts[1];
                $search++;
            }
        }
        if ($search) {
            \file_put_contents($file, \implode($rows));
            @\chmod($file, 0664);
        }
    }

    private function example(): void
    {

    }

    private function undoChecker(string $file): void
    {
        $rows = \file($file);
        $search = 0;
        foreach ($rows as &$row) {
            if (\str_contains($row, self::REPLACE_START) && \str_contains($row, self::REPLACE_END)) {
                $parts = \explode(self::REPLACE_START, $row);
                if (\count($parts) !== 2) {
                    continue;
                }
                $endParts = \explode(self::REPLACE_END, $parts[1]);
                if (\count($endParts) !== 2) {
                    continue;
                }
                $message = \trim($endParts[0]);
                $row = $parts[0] . self::ORIGIN_START . ' ' .  $message . ' ' . self::ORIGIN_END . $endParts[1];
                $search++;
            }
        }
        if ($search) {
            \file_put_contents($file, \implode($rows));
            @\chmod($file, 0664);
        }
    }

    private function changeMode(string $function): void
    {
        $dir = SystemSettings::getRealPath($this->path);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $item) {
            if ($item->isFile() && $item->getExtension() === 'php') {
                $this->$function($item->getRealPath());
            }
        }
    }
}
