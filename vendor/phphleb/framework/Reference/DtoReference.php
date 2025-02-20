<?php


namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Main\Insert\ContainerUniqueItem;

#[Accessible] #[AvailableAsParent]
class DtoReference extends ContainerUniqueItem implements DtoInterface, Interface\Dto, RollbackInterface
{
    private static array $data = [];

    public function __construct()
    {
        $this->rollback();
    }

    #[\Override]
    public function get($name)
    {
        return self::$data[$name] ?? null;
    }

    #[\Override]
    public function set($name, #[\SensitiveParameter] $value): void
    {
        self::$data[$name] = $value;
    }

    #[\Override]
    public function clear(): void
    {
        $this->rollback();
    }

    #[\Override]
    public function list(): array
    {
        return self::$data;
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$data = [];
    }
}
