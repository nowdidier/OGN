<?php


namespace Hleb\Constructor\Data;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final readonly class View implements \Stringable
{

    public function __construct(
        public string $template,
        public array  $params = [],
        public ?int $status = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'template' => $this->template,
            'params' => $this->params,
            'status' => $this->status,
        ];
    }

    #[\Override]
    public function __toString(): string
    {
        return \template($this->template, $this->params);
    }
}
