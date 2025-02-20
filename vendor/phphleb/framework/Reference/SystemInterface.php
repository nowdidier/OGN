<?php

namespace Hleb\Reference;

use Hleb\Database\PdoManager;

interface SystemInterface
{

    public function getRouteName(): ?string;

    public function getActualLogLevel(): string;

    public function getLogLevelList(): array;

    public function getRouteCacheData(): array;

    public function getRouteCacheInfo(): array;

    public function getStartTime(): ?float;

    public function getEndTime(): ?float;

    public function getCoreEndTime(): ?float;

    public function getRequestId(): string;

    public function getLibraryKey(): string;

    public function getDataFromDA(?string $key = null): array;

    public function getClassesAutoloadDataFromDA(): array;

    public function getInsertTemplateDataFromDA(): array;

    public function getMiddlewareDataFromDA(): array;

    public function getInitiatorDataFromDA(): array;

    public function getDebugDataFromDA(): array;

    public function getHlCheckDataFromDA(): array;

    public function getDbDebugDataFromDA(): array;

    public function getHlebVersionAsConsoleFormat(): string;

    public function getRoutesAsConsoleFormat(): string;

    public function getPdoManager(#[\SensitiveParameter] ?string $configKey = null): PdoManager;

    public function updateRouteCacheAsConsoleFormat(): string;

    public function getFrameworkApiVersion(): string;

    public function getFrameworkResourcePrefix(): string;

    public function getFrameworkVersion(): string;

    public function getVersion(): string;

    public function getApiVersion(): string;

    public function isWebConsoleActive(): bool;

    public function createSqlQueryLog(
        float   $startTime,
        string  $query,
        ?string $configKey = null,
        string  $tag = 'special',
        ?string $driver = null,
    ): void;

    public function createCustomLog(
        #[\SensitiveParameter] string $sql,
        float $microtime,
        array $params = [],
        ?string $dbname = null,
        ?string $driver = null,
    ): void;

    public static function getTaskPermissions(string $taskClass): array;
}
