<?php

namespace Hleb\Reference;

interface RedirectInterface
{

    public function to(string $location, int $status = 302): void;
}
