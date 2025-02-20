<?php

namespace Hleb\Reference;

use Hleb\Static\Cache;

interface TemplateInterface
{

    public function get(string $viewPath, array $extractParams = [], array $config = []): string;

    public function insert(string $viewPath, array $extractParams = [], array $config = []): void;

    public function insertCache(string $viewPath, array $extractParams = [], int $sec = Cache::DEFAULT_TIME, array $config = []): void;
}
