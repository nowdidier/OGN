<?php


namespace Hleb\Constructor\Attributes\Task;

use JetBrains\PhpStorm\ExpectedValues;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly final class Purpose
{

    final public const FULL = 'full';

    final public const CONSOLE = 'console';

    final public const EXTERNAL = 'external';

    public function __construct(
        #[ExpectedValues([
            Purpose::FULL,
            Purpose::CONSOLE,
            Purpose::EXTERNAL,
        ])]
        public string $status = Purpose::FULL
    )
    {
    }
}
