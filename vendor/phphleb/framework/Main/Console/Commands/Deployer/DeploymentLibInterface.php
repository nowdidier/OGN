<?php

namespace Hleb\Main\Console\Commands\Deployer;

interface DeploymentLibInterface
{

    public function __construct(array $config);

    public function help(): string|false;

    public function add(): int;

    public function remove(): int;

    public function classmap(): array;

    public function noInteraction(): void;

    public function quiet(): void;
}
