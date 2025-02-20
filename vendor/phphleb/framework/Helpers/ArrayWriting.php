<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class ArrayWriting
{

    public function getString(array $array, int $level = 0): string
    {
       return \trim($this->cycle($array, $level), ' ');

    }

    private function cycle(array $array, int $level = 0): string
    {
        if (\count($array) === 0) {
            return ' []';
        }
        $result = ' [' . PHP_EOL;
        $isAssoc = ArrayHelper::isAssoc($array);
        foreach ($array as $key => $value) {
            if (!$isAssoc) {
                $tag = '';
            } else if (\is_int($key)) {
                $tag = "$key => ";
            } else {
                $tag = "'$key' => ";
            }

            if (\is_string($value)) {
                $value = "'$value'";
            }
            if (\is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            if ($value === null) {
                $value = 'null';
            }
            if (\is_array($value)) {
                $value = $this->cycle($value, $level + 4);
            }

            $result .= \str_repeat(' ', $level + 4) . $tag . $value . ',' . PHP_EOL;
        }

        return $result . \str_repeat(' ', $level) . ']';
    }
}
