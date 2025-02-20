<?php

namespace task_namespace_template;

use Hleb\Base\Task;

class task_class_template extends Task
{
    #[\Override]
    protected function rules(): array
    {
        return [];
    }

    protected function run(?string $arg = null)
    {

    }
}
