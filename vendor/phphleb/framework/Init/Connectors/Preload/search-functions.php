<?php

if (!function_exists('search_root')) {

    function search_root(): string|false
    {
        $base = __DIR__ . '/../../../../../';
        for ($i = 0; $i < 3; $i++) {
            $search = \realpath($base . '/../') . DIRECTORY_SEPARATOR;
            if (\is_dir($search . 'app') && \is_dir($search . 'routes')) {
                return $search;
            }
        }
        return false;
    }
}

if (!function_exists('search_php_files')) {

    function search_php_files(string $dir): array
    {
        $result = [];
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $result[] = $file->getRealPath();
            }
        }
        return $result;
    }
}
