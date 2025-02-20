<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class NameConverter
{

    public function convertStrToClassName(string $str): string
    {
        $str = str_replace('/', '\\', \strtolower($str));
        $str = \implode('\\', \array_map('ucfirst', \explode('\\', $str)));

        return \implode(\array_map('ucfirst', \explode('-', $str)));
    }

    public function convertClassNameToStr(string $className): string
    {
        if ($className[0] === strtolower($className[0])) {
            return $className;
        }
        $parts = [];
        \preg_match_all('/[A-Z][^A-Z]+/s', $className, $result, PREG_SET_ORDER);
        foreach ($result as $value) {
            $parts[] = $value[0] ?? '';
        }
        return \strtolower(\implode('-', $parts));
    }
}
