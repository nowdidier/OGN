<?php


namespace Hleb\Main\Logger;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;

#[AvailableAsParent] #[Accessible]
class NullLogger  implements LoggerInterface, \Hleb\Reference\Interface\Log
{

    #[\Override]
    public function emergency(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function alert(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function critical(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function error(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function warning(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function notice(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function info(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function debug(\Stringable|string $message, array $context = []): void
    {
    }

    #[\Override]
    public function log(mixed $level, \Stringable|string $message, array $context = []): void
    {
    }
}
