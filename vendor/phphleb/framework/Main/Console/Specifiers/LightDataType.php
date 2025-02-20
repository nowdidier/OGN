<?php

declare(strict_types=1);

namespace Hleb\Main\Console\Specifiers;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Helpers\DefaultValueHelper;

#[Accessible]
final readonly class LightDataType
{

    public function __construct(public mixed $value)
    {
    }

    public function toInt(): int
    {
        return is_numeric($this->value) ? (integer)$this->value : 0;
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public function limitInt(int $min = 0, int $max = PHP_INT_MAX, int $default = 0, bool|string $exc = false): int
    {
        if (!\is_numeric($this->value)) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        $value = $this->toInt();
        if ($value > $max) {
            return $max;
        }
        if ($value < $min) {
            return $min;
        }
        return $value;
    }

    public function asInt(int $default = 0, bool|string $exc = false): int
    {
        if (!\is_numeric($this->value)) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        return (int)$this->value;
    }

    public function asPositiveInt(bool|string $exc = false): int
    {
        if (!\is_numeric($this->value) || (int)$this->value < 0) {
            DefaultValueHelper::err($exc);
            return 0;
        }
        return (int)$this->value;
    }

    public function asFloat(float $default = 0.0, int $precision = 5, int $mode = PHP_ROUND_HALF_UP, bool|string $exc = false): float
    {
        if (!\is_numeric($this->value)) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        return \round((float)$this->value, $precision, $mode);
    }

    public function asPositiveFloat(int $precision = 5, int $mode = PHP_ROUND_HALF_UP, bool|string $exc = false): float
    {
        if (!\is_numeric($this->value) || (float)$this->value < 0) {
            DefaultValueHelper::err($exc);
            return 0.0;
        }
        return \round((float)$this->value, $precision, $mode);
    }

    public function asString(string|null $default = null, bool|string $exc = false): string|null
    {
        if ($this->value === null) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        $type = \gettype($this->value);
        if (!in_array($type, ['boolean', 'integer', 'string', 'double', 'float'])) {
            DefaultValueHelper::err($exc, "The value cast to a string cannot be processed due to the type: $type");
            return $default;
        }

        return (string)$this->value;
    }

    public function asBool(bool $default = false, array $correct = [true, 'true', 'TRUE', '1', 1], bool|string $exc = false): bool
    {
        if ($this->value === null) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        return \in_array($this->value, $correct, true);
    }

    public function asArray(array $default = [], bool|string $exc = false): array
    {
        if ($this->value === null) {
            DefaultValueHelper::err($exc);
            return $default;
        }
        if (\is_string($this->value) &&
            (\str_starts_with(\ltrim($this->value), '{') || \str_starts_with(\ltrim($this->value), '['))
        ) {
            $value = $this->value;
            try {
                $value = \json_decode(\trim($value), true, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
            }
            if (!\is_array($value)) {
                DefaultValueHelper::err($exc, 'Failed to convert string value to array.');
                return $default;
            }
            return $value;
        }
        if (!\is_array($this->value)) {
            DefaultValueHelper::err($exc, 'The value is not an array.');
            return $default;
        }

        return $this->value;
    }

    public function value(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
