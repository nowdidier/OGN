<?php


namespace Hleb\Main\Console\Specifiers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class ArgType
{
    final public const TYPES = ['integer', 'number', 'string', 'list', 'label'];

    final public const WITH_LABEL = ['name', 'label', 'desc'];

    private array $data;

    public function __construct(?string $name = null)
    {
        $this->data = ['name' => $name];
    }

    public function default(mixed $value = null): static
    {
        $this->data['default'] = $value;

        return $this;
    }

    public function short(string $name): static
    {
        if (!isset($this->data['shortName'])) {
            $this->data['shortName'] = [];
        }
        $this->data['shortName'][] = $name;

        return $this;
    }

    public function list(int $minCount = 0, ?int $maxCount = null): static
    {
        $this->data['list'] = true;
        $this->data['minCount'] = $minCount;
        $this->data['maxCount'] = $maxCount;

        return $this;
    }

    public function number(float|int|null $min = null, float|int|null $max = null): static
    {
        $this->data['number'] = true;
        $this->data['min'] = $min;
        $this->data['max'] = $max;

        return $this;
    }

    public function integer(?int $min = null, ?int $max = null): static
    {
        $this->data['integer'] = true;
        $this->data['min'] = $min;
        $this->data['max'] = $max;

        return $this;
    }

    public function string(int $minLength = 0, ?int $maxLength = null): static
    {
        $this->data['string'] = true;
        $this->data['minLength'] = $minLength;
        $this->data['maxLength'] = $maxLength;

        return $this;
    }

    public function required(): static
    {
        $this->data['required'] = true;

        return $this;
    }

    public function label(): static
    {
        $this->data['label'] = true;

        return $this;
    }

    public function desc(string $text): static
    {
        $this->data['description'] = $text;

        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
