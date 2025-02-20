<?php

namespace Hleb\Reference;

interface DebugInterface
{

    public function send(mixed $data, ?string $name = null): void;

    public function getCollection(): array;

    public function setHlCheck(string $message, ?string $file = null, ?int $line = null): void;

    public function isActive(): bool;
}
