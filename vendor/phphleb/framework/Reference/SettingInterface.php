<?php

namespace Hleb\Reference;

interface SettingInterface
{

    public function isStandardMode(): bool;

    public function isAsync(): bool;

    public function isCli(): bool;

    public function isDebug(): bool;

    public function getRealPath(string $keyOrPath): false|string;

    public function getPath(string $keyOrPath): false|string;

    public function isEndingUrl(): bool;

    public function getParam(string $name, string $key): mixed;

    public function common(string $key): mixed;

    public function main(string $key): mixed;

    public function database(string $key): mixed;

    public function system(string $key): mixed;

    public function getModuleName(): ?string;

    public function getControllerMethodName(): ?string;

    public function getDefaultLang(): string;

    public function getAutodetectLang(): string;

    public function getAllowedLanguages(): array;

    public function getInitialRequest(): object;
}
