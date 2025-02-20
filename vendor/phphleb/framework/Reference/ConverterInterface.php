<?php

namespace Hleb\Reference;

use Phphleb\PsrAdapter\Psr11\IntermediateContainerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

interface ConverterInterface
{

    public function toPsr3Logger(): LoggerInterface;

    public function toPsr11Container(): ContainerInterface;

    public function toPsr16SimpleCache(): \Psr\SimpleCache\CacheInterface;
}
