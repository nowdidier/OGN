<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Static\Settings;
use JsonException;
use Phphleb\Nicejson\JsonConverter;

#[Accessible]
final readonly class StorageLibConfigurator
{
    private string $path;

    public function __construct(string $component)
    {
        $this->path = Settings::getPath("@storage/lib/$component");
    }

    public function getConfig(string $file): array|false
    {
        if (!\file_exists($this->path)) {
            return false;
        }
        $path = $this->path . DIRECTORY_SEPARATOR . \rtrim($file, '\\/');
        if (!\file_exists($path)) {
            throw new \DomainException("No config file found at $path");
        }
        return \json_decode(\file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    public function setConfigOption(string $file, string $name, mixed $value, string $type = 'string'): bool
    {
        \settype($value, $type);
        $config = $this->getConfig($file);
        if ($config === false) {
            return false;
        }
        if (\array_key_exists($name, $config) && $config[$name] === $value) {
            return true;
        }
        $config[$name] = $value;
        $converter = new JsonConverter();
        $path = $this->path . DIRECTORY_SEPARATOR . \rtrim($file, '\\/');

        $result = \file_put_contents($path, $converter->get($config)) !== false;

        @\chmod($path, 0664);

        return $result;
    }
}
