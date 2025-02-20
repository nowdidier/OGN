<?php


namespace Hleb\Reference;

use Hleb\Base\Task;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;

#[Accessible] #[AvailableAsParent]
class CommandReference implements CommandInterface, Interface\Command
{

    #[\Override]
    public function execute(Task $task, array $arguments = []): mixed
    {
        $task->call($arguments);

        return $task->getExecResult();
    }
}
