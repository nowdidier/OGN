<?php


namespace Hleb\Constructor\Protected;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Data\Key;
use Hleb\Static\Request;
use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Static\Session;

final class Csrf extends BaseAsyncSingleton implements RollbackInterface
{
    final public const KEY_NAME = '_token';

    final public const X_TOKEN = 'X-CSRF-Token';

    private const SESSION_KEY = 'HL-CSRF-TOKEN';

    private static ?string $key = null;

    public static function validate(?string $key): bool
    {
        return self::key() === $key;
    }

   public static function discover(): string|null
   {
         return  Request::post(self::KEY_NAME)->asString() ??
           Request::get(self::KEY_NAME)->asString() ??
           Request::getSingleHeader(self::X_TOKEN)->asString();
   }

    public static function key(): string
    {
        if (self::$key) {
            return self::$key;
        }
        if (!empty($_SESSION[self::SESSION_KEY])) {
            self::$key = $_SESSION[self::SESSION_KEY];
        } else {
            $id = Session::getSessionId();
            self::$key = $id ? \sha1( $id . Key::get()) : \sha1( \rand() . Key::get());
            $_SESSION[self::SESSION_KEY] = self::$key;
        }

        return self::$key;
    }

    #[\Override]
    public static function rollback(): void
    {
        self::$key = null;
    }
}
