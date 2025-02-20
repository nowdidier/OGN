<?php

namespace Hleb\Reference;

use JetBrains\PhpStorm\NoReturn;

interface ScriptInterface
{

    #[NoReturn]
    public static function standardExit($message = '', int $httpCode = 200, array $headers = []): never;

    public static function asyncExit($message = '', ?int $httpStatus = null, array $headers = []): never;
}
