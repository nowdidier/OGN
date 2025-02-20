<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class DirectoryInspector
{

    public function isDirectoryEntry(string $sampleDir, array $checkedDirs): bool
    {
        $sampleDir = $this->formatDirectory($sampleDir);
        foreach ($checkedDirs as $dir) {
            $dir = $this->formatDirectory($dir);
            if (\str_starts_with($dir, $sampleDir)) {
                return true;
            }
        }
        return false;
    }

    public function getRelativeDirectory(string $rootDir, string $fullDir): string|false
    {
        $rootDir = $this->formatDirectory($rootDir);
        $fullDir = $this->formatDirectory($fullDir);
        if (!\str_starts_with($fullDir, $rootDir)) {
           return false;
        }
        $search = \str_replace($rootDir, '', $fullDir, $count);
        if ($count > 1) {
            return false;
        }
        return \str_replace('\\', '/', \trim($search, DIRECTORY_SEPARATOR));
    }

    private function formatDirectory(string $dir): string
    {
        return \trim(\str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $dir), DIRECTORY_SEPARATOR);
    }
}
