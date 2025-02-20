<?php

namespace Hleb\Main\Console\Commands\Features;

interface FeatureInterface
{
    public function run(array $argv): string|false;

    public static function getDescription(): string;

    public function getCode(): int;
}
