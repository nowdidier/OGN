<?php

declare(strict_types=1);

namespace Hleb\Main\Routes\Update;

use FilesystemIterator;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Main\Routes\Search\RouteFileManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class CheckRouteForUpdates
{
    public function __construct(
        private int|float $time,
        private string $routeDir,
    )
    {
    }

    public function hasChanges(?string $hash = null): bool
    {
        $fileInfo = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->routeDir, FilesystemIterator::SKIP_DOTS)
        );
        $map = [];

        foreach ($fileInfo as $data) {
            if (!$data->isFile() || $data->getExtension() !== 'php') {
                continue;
            }
            $path = $data->getRealPath();
            if (\filemtime($path) > (int)$this->time) {
                return true;
            }

            $map[] = $path;
        }
        if (SystemSettings::getSystemValue('route.files.checking')) {


            if ($hash !== \sha1(\json_encode($map))) {
                return true;
            }
        }

        return false;
    }

}
