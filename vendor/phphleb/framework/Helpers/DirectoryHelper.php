<?php


namespace Hleb\Helpers;

use CallbackFilterIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class DirectoryHelper
{

    public static function getMbSize(string $dir): float|false
    {
        if (!\is_dir($dir)) {
            return false;
        }

        if (!\str_starts_with(\strtoupper(\php_uname('s')), 'WINDOWS')) {
            try {
                $result = (string)@\shell_exec("du -s $dir");
                $parts = \explode("\t", \trim($result));
                if (\count($parts) === 2 && \is_numeric($parts[0])) {
                    return \round((int)$parts[0] / 1024, 2);
                }
            } catch (\Throwable) {
            }
        }
        try {
            $iterator = self::getFileIterator($dir);
        } catch (\Throwable) {
            return false;
        }

        $size = 0;

        foreach ($iterator as $item) {
            $c = $item->getSize();
            if ($c === false) {
                return false;
            }
            $size += (float)$c;
        }
        if (!$size) {
            return 0;
        }
        return \round($size / 1024 / 1024, 2);
    }

    public static function getFileIterator(string $path): CallbackFilterIterator
    {
        return new CallbackFilterIterator(
            new RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            ),
            function (SplFileInfo $current) {
                return $current->isFile();
            }
        );
    }
}
