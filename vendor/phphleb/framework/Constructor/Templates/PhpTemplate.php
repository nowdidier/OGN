<?php


namespace Hleb\Constructor\Templates;

use Hleb\Constructor\Data\SystemSettings;

final class PhpTemplate implements TemplateInterface
{
    public function __construct(
        private string $path,
        private array $data,
    )
    {
    }

    public function view(): void
    {
        \extract($this->data);
        unset($this->data);
        if (!\str_ends_with($this->path, '.php')) {
            $this->path = "{$this->path}.php";
        }

        require SystemSettings::getRealPath($this->path);
    }
}
