<?php

namespace Hleb\Reference;

interface CsrfInterface
{

    public function token(): string;

    public function field(): string;

    public function validate(?string $key): bool;

    public function discover(): string|null;
}
