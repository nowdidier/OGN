<?php


namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\CoreProcessException;
use Hleb\Main\Insert\ContainerUniqueItem;
use Hleb\Static\Cookies;

#[Accessible] #[AvailableAsParent]
class SessionReference extends ContainerUniqueItem implements SessionInterface, Interface\Session, RollbackInterface
{

    protected const FLASH_ID = "_hl_flash_";

    #[\Override]
    public function all(): array
    {
        $all =  $_SESSION ?? [];
        unset($all[self::FLASH_ID]);

        return $all;
    }

    #[\Override]
    public function get(int|string $name, mixed $default = null): mixed
    {
        $all =  $_SESSION ?? [];
        unset($all[self::FLASH_ID]);

        $result = $all[$name] ?? null;
        if (!\is_null($result)) {
            return $result;
        }
        if (\is_callable($default)) {
            return $default();
        }
        return $default;
    }

    #[\Override]
    public function set(int|string $name, float|int|bool|array|string|null $data): void
    {
        if ($name === self::FLASH_ID) {
            throw new CoreProcessException('You cannot directly change the value of a special identifier for flash sessions.');
        }
        $_SESSION[$name] = $data;
    }

    #[\Override]
    public function getSessionId(): string|null
    {
        return (SystemSettings::isAsync() ? Cookies::getSessionId() : \session_id()) ?: null;
    }

    #[\Override]
    public function delete(int|string $name): void
    {
        if ($name === self::FLASH_ID) {
            throw new CoreProcessException('You cannot directly delete the value of a special identifier for flash sessions.');
        }
        if (\array_key_exists($name, $_SESSION ?? [])) {
            unset($_SESSION[$name]);
        }
    }

    #[\Override]
    public function clear(): void
    {
        foreach($_SESSION ?? [] as $name => $item) {
            unset($_SESSION[$name]);
        }
    }

    #[\Override]
    public static function rollback(): void
    {
        foreach($_SESSION ?? [] as $name => $item) {
            unset($_SESSION[$name]);
        }
    }

    #[\Override]
    public function has(int|string $name): bool
    {
        return \array_key_exists($name, $_SESSION ?? []);
    }

    #[\Override]
    public function exists(int|string $name): bool
    {
        return isset($_SESSION[$name]) && $_SESSION[$name] !== '';
    }

    #[\Override]
    public function setFlash(string $name, float|int|bool|array|string|null $data, int $repeat = 1): void
    {
        if (!isset($_SESSION[self::FLASH_ID])) {
            $_SESSION[self::FLASH_ID] = [];
        }

        if (\is_null($data) || $repeat < 1) {
            unset($_SESSION[self::FLASH_ID][$name]);
        } else {
            $_SESSION[self::FLASH_ID][$name] = [
                'new' => $data,
                'old' => null,
                'reps_left' => $repeat,
            ];
        }

    }

    #[\Override]
    public function clearFlash(): void
    {
        $_SESSION[self::FLASH_ID] = [];
    }

    #[\Override]
    public function allFlash(): array
    {
        return $_SESSION[self::FLASH_ID] ?? [];
    }

    #[\Override]
    public function getFlash(string $name, string|float|int|array|bool|null $default = null): string|float|int|array|bool|null
    {
       return $_SESSION[self::FLASH_ID][$name]['old'] ?? $default;
    }

    #[\Override]
    public function hasFlash(string $name, string $type = 'old'): bool
    {
        if ($type === 'all') {
            return !\is_null($_SESSION[self::FLASH_ID][$name] ?? null);
        }
        if ($type === 'new' || $type === 'old') {
            return !\is_null($_SESSION[self::FLASH_ID][$name][$type] ?? null);
        }
        throw new CoreProcessException('The flash type can only be `new`, `old` or `all`.');
    }

    #[\Override]
    public function increment(string $name, int $amount = 1): void
    {
        if ($amount <= 0) {
            throw new CoreProcessException('The increment must be greater than zero.');
        }
        $this->counter($name, $amount);
    }

    #[\Override]
    public function decrement(string $name, int $amount = 1): void
    {
        if ($amount <= 0) {
            throw new CoreProcessException('The decrement must be greater than zero.');
        }
        $this->counter($name, -$amount);
    }

    #[\Override]
    public function counter(string $name, int $amount): void
    {
        if (!$this->has($name)) {
            $_SESSION[$name] = 0;
        }
        if (!\is_numeric($_SESSION[$name])) {
            throw new CoreProcessException('The value for the counter must be numeric.');
        }

        $_SESSION[$name] += $amount;
    }
}
