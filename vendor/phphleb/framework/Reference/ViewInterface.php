<?php

namespace Hleb\Reference;

use Hleb\Constructor\Data\View;

interface ViewInterface
{

    public function view(string $template, array $params = [], ?int $status = null): View;
}
