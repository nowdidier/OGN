<?php

declare(strict_types=1);

namespace Hleb\Database;

final class StandardDB extends SystemDB
{
    protected static string $dbName = 'default.database';

    public static function getStandardPdoInstance(#[\SensitiveParameter] ?string $configKey = null): PdoManager
    {
        if (!isset(self::$defaultConnList[$configKey])) {
            $pdo = self::getNewPdoInstance($configKey);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            self::$defaultConnList[$configKey] = $pdo;
        }

        return new PdoManager(self::$defaultConnList[$configKey], self::getConfigKey($configKey));
    }
}
