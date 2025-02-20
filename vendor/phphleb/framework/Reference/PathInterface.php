<?php

namespace Hleb\Reference;

use Hleb\Main\Info\PathInfoDoc;

interface PathInterface
{

    public function relative(string $path): string;

    public function createDirectory(string $path, int $permissions = 0775): bool;

    public function exists(string $path): bool;

    public function contents(string $path, bool $use_include_path = false, $context = null, int $offset = 0, ?int $length = null): false|string;

    public function put(string $path, mixed $data, int $flags = 0, $context = null): false|int;

    public function isDir(string $path): bool;

    public function getReal(string $keyOrPath): false|string;

    public function get(string $keyOrPath): false|string;
}
