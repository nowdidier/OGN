<?php


namespace Hleb\Reference;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Database\PdoManager;
use Hleb\Database\SystemDB;
use Hleb\Main\Insert\ContainerUniqueItem;
use PDO;

#[Accessible] #[AvailableAsParent]
class DbReference extends ContainerUniqueItem implements DbInterface, Interface\Db
{

    #[\Override]
    public function run(#[\SensitiveParameter] string $sql, #[\SensitiveParameter] array $args = [], ?string $configKey = null): false|\PDOStatement
    {
        return SystemDB::run($sql, $args, $configKey);
    }

    #[\Override]
    public function dbQuery(#[\SensitiveParameter] string $sql, ?string $configKey = null): false|array
    {
        return SystemDB::dbQuery($sql, $configKey);
    }

    public function getPdoInstance(?string $configKey = null): PdoManager
    {
        return SystemDB::getPdoInstance($configKey);
    }

    public function getNewPdoInstance(?string $configKey = null): PDO
    {
        return SystemDB::getNewPdoInstance($configKey);
    }

    public function getConfig(?string $configKey = null): ?array
    {
        return SystemDB::getConfig($configKey);
    }

    #[\Override]
    public function quote(string $value, int $type = PDO::PARAM_STR, ?string $config = null): string
    {
        return SystemDB::quote($value, $type, $config);
    }

    #[\Override]
    public static function rollback(): void
    {
       SystemDB::rollback();
    }
}
