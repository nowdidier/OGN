<?php

namespace Hleb\Reference;

interface OnceInterface
{

    public static function get(callable $func): mixed;

    public static function rollback(): void;
}
