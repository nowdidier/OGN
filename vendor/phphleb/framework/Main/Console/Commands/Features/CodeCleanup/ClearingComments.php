<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Commands\Features\CodeCleanup;

use FilesystemIterator;
use Hleb\Helpers\PhpCommentHelper;
use Hleb\Main\Console\Commands\Features\FeatureInterface;
use Hleb\Static\Path;
use Hleb\Static\Settings;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class ClearingComments implements FeatureInterface
{
    private const DESCRIPTION = 'Clean up comments to reduce code size.';

    private string $path = '@framework';

    #[\Override]
    public function run(array $argv): string
    {
        if (!empty($argv[1])) {
            $this->path = $argv[1];
            if (!Path::getReal($this->path)) {
                throw new \RuntimeException('Wrong path to process!');
            }
        }
        $count = 0;
        $dir = Settings::getRealPath($this->path);
        $classes = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );
        $helper = new PhpCommentHelper();

        foreach ($classes as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }
            $path = $file->getRealPath();
            $content = \file_get_contents($path);
            $content = $helper->clearMultiLine($content);
            $content = $helper->clearOneLiner($content);

            \file_put_contents($path, $content);
            @\chmod($path, 0664);
            $count++;
        }

        return "Success! Changes were made to $count files. Comments have been removed." . PHP_EOL;
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
}
