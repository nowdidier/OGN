<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final readonly class PhpCommentHelper
{

    public function clearOneLiner(string $code): string
    {
        return \preg_replace('/^\s*\/\/.*$/m', '', $code);
    }

    public function clearMultiLine(string $code): string
    {
        return \preg_replace('/^\s*\/\*.*?(\*\/)/ms', '', $code);
    }

}
