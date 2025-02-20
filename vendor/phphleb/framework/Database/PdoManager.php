<?php

declare(strict_types=1);

namespace Hleb\Database;

use Hleb\Constructor\Attributes\Accessible;
use PDO;
use PDOStatement;

#[Accessible]
final class PdoManager
{
    protected PDO $pdo;

    protected ?string $driver = null;

    public function __construct(#[\SensitiveParameter] PDO $pdo, readonly string $configKey)
    {
        $this->pdo = $pdo;
        $this->driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function getLabel(): string
    {
        return $this->configKey;
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public function errorCode()
    {
        return $this->pdo->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->pdo->errorInfo();
    }

    public function exec(#[\SensitiveParameter] string $statement): false|int
    {

        SystemDB::createLog(\microtime(true), $statement, $this->configKey, previously: true, driver: $this->driver);

        return $this->pdo->exec($statement);
    }

    public function getAttribute(#[\SensitiveParameter] int $attribute): mixed
    {
        return $this->pdo->getAttribute($attribute);
    }

    public function getAvailableDrivers(): array
    {
        return $this->pdo::getAvailableDrivers();
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    public function lastInsertId($name = null): false|string
    {
        return $this->pdo->lastInsertId($name);
    }

    public function prepare( #[\SensitiveParameter] string $query,  #[\SensitiveParameter] array  $options = []): false|PDOStatement
    {
        SystemDB::createLog(\microtime(true), $query, $this->configKey, 'prepare', previously: true, driver: $this->driver);

        return $this->pdo->prepare($query, $options);
    }

    public function query(#[\SensitiveParameter] string $query, int|null $fetchMode = null): false|PDOStatement
    {
        SystemDB::createLog(\microtime(true), $query, $this->configKey, previously: true, driver: $this->driver);

        return $this->pdo->query($query, $fetchMode);
    }

    public function quote(#[\SensitiveParameter] string $query, int|null $fetchMode = null): false|string
    {
        return $this->pdo->quote($query, $fetchMode);
    }

}
