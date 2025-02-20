<?php


namespace Hleb\Helpers;

final class RangeChecker
{
    protected array $range;

    public function __construct(string|array $range)
    {
        $this->range = \is_array($range) ? $range : \explode(',', $range);
    }

    public function check(int $number): bool
    {
        foreach ($this->range as $range) {
            if (\is_numeric($range)) {
                if ((int)$range === $number) {
                    return true;
                }
            } else if (\is_string($range) && $range !== '') {
                $i = \explode('-', $range);
                $count = \count($i);
                if ($count > 2 && $count < 5) {
                    if ($count === 3) {
                        $i = \str_contains($range, '--') ? [$i[0], '-' . $i[2]] : ['-' . $i[1], $i[2]];
                    } else if ($count === 4) {
                        $i = ['-' . $i[1], '-' . $i[3]];
                    }
                }
                if (\count($i) === 2) {
                    if (\is_numeric($i[0]) && $number >= (int)$i[0]) {
                        if (\end($i) === '∞' || (\is_numeric($i[1]) && $number <= (int)$i[1])) {
                            return true;
                        }
                    }
                    if ($i[0] === '-∞' && \is_numeric($i[1]) && $number <= (int)$i[1]) {
                        return true;
                    }
                }
            } else {
                break;
            }
        }
        return false;
    }

    public function validation(bool $onlyPositive = false): bool
    {
        $str = \implode(',', $this->range);
        if (\str_contains($str, '∞')) {
            if (\substr_count($str, '∞') > 1 || (!\str_ends_with($str, '∞') && !\str_starts_with($str, '-∞'))) {
                return false;
            }
        }
        foreach ($this->range as $range) {
            if (\is_numeric($range)) {
                continue;
            }
            if ($onlyPositive && \substr_count($range, '-') !== 1) {
                return false;
            }
            if (!\is_numeric(\str_replace(['-', '∞'], '', $range)) || \substr_count($range, '-') > 3) {
                return false;
            }
            if (\str_starts_with($range, '--') || \str_ends_with($range, '-') || \str_ends_with($range, '--')) {
                return false;
            }
            if (!$onlyPositive && \substr_count($range, '-') > 1 && !\substr_count($range, '--')) {
                return false;
            }
        }

        return true;
    }
}
