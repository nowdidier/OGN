<?php

namespace Hleb\Reference;

use PDO;

interface DbInterface
{

    public function run(string $sql, array $args = [], ?string $configKey = null): false|\PDOStatement;

    public function dbQuery(string $sql, ?string $configKey = null): false|array;

    public function quote(string $value, int $type = PDO::PARAM_STR, ?string $config = null): string;

    public static function rollback(): void;
}
