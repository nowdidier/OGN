<?php


namespace Hleb\Main\System;

use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\HttpMethods\External\SystemRequest;

final readonly class LibraryServiceResources
{
    private ?SystemRequest $request;

    public function __construct()
    {
        $this->request = DynamicParams::getRequest();
    }

    public function place(): bool
    {
        $address = $this->request->getUri()->getPath();
        $key = LibraryServiceAddress::KEY;

        if (\str_starts_with($address, "/$key/")) {
            return $this->getResource();
        }
        return false;
    }

    private function getResource(): bool
    {
        $parts = \explode('/', \trim($this->request->getUri()->getPath(), '/'));
        if (\count($parts) !== 5) {
            return false;
        }
        \array_shift($parts);

        if (!$parts) {
            return false;
        }
        $systemPath = SystemSettings::getRealPath('@library/' . $parts[0]);
        if (!$systemPath) {
            return false;
        }

        $file = \implode(DIRECTORY_SEPARATOR, [$systemPath, 'web', 'index.php']);
        if (!\file_exists($file)) {
            return false;
        }

        $request = $this->request;

        return (require $file) ?? false;
    }
}
