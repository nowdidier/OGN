<?php


namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\HttpMethods\Intelligence\Cookies\AsyncCookies;
use Hleb\HttpMethods\Intelligence\Cookies\StandardCookies;
use Hleb\HttpMethods\Specifier\DataType;
use Hleb\Main\Insert\ContainerUniqueItem;

#[Accessible] #[AvailableAsParent]
class CookieReference extends ContainerUniqueItem implements CookieInterface, Interface\Cookie, RollbackInterface
{

    private static string $performer;

    public function __construct()
    {
        self::$performer = SystemSettings::isAsync() ? AsyncCookies::class : StandardCookies::class;
    }

    #[\Override]
    public function set(string $name, string $value = '', array $options = []): void
    {
        if (empty($options['path'])) {
            $options['path'] = '/';
        }

        self::$performer::set($name, $value, $options);
    }

    #[\Override]
    public function get(string $name): DataType
    {
        return self::$performer::get($name);
    }

    #[\Override]
    public function all(): array
    {
        return self::$performer::all();
    }

    #[\Override]
    public function setSessionName(string $name): void
    {
        self::$performer::setSessionName($name);
    }

    #[\Override]
    public function getSessionName(): string
    {
        return self::$performer::getSessionName();
    }

    #[\Override]
    public function setSessionId(string $id): void
    {
        self::$performer::setSessionId($id);
    }

    #[\Override]
    public function getSessionId(): string
    {
        return self::$performer::getSessionId();
    }

    #[\Override]
    public function delete(string $name): void
    {
        self::$performer::delete($name);
    }

    #[\Override]
    public function clear(): void
    {
        self::$performer::clear();
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$performer::rollback();
    }

    #[\Override]
    public function has(string $name): bool
    {
        return self::$performer::get($name)->value() !== null;
    }

    #[\Override]
    public function exists(string $name): bool
    {
        $value = self::$performer::get($name)->value();

        return $value !== '' && $value !== null;
    }
}
