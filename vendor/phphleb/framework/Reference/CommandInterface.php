<?php

namespace Hleb\Reference;

use Hleb\Base\Task;

interface CommandInterface
{

    public function execute(Task $task, array $arguments): mixed;
}
