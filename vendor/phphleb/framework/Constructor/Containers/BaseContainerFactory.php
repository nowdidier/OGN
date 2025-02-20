<?php


namespace Hleb\Constructor\Containers;

use Hleb\Main\Insert\BaseAsyncSingleton;
use Hleb\Reference\Interface\{
    Arr,
    Cache,
    Command,
    Converter,
    Cookie,
    Csrf,
    Db,
    Debug,
    DI,
    Dto,
    Log,
    Path,
    Redirect,
    Request,
    Response,
    Router,
    Session,
    Setting,
    System,
    Template,
};
use Hleb\Reference\{ArrInterface,
    CacheInterface,
    CommandInterface,
    ConverterInterface,
    CookieInterface,
    CsrfInterface,
    DbInterface,
    DebugInterface,
    DiInterface,
    DtoInterface,
    LogInterface,
    PathInterface,
    RedirectInterface,
    RequestInterface,
    ResponseInterface,
    RouterInterface,
    SessionInterface,
    SettingInterface,
    SystemInterface,
    TemplateInterface
};

abstract class BaseContainerFactory extends BaseAsyncSingleton
{

    public const SERVICE_MAP = [
        Arr::class => ArrInterface::class,
        Csrf::class => CsrfInterface::class,
        Converter::class => ConverterInterface::class,
        Request::class => RequestInterface::class,
        Response::class => ResponseInterface::class,
        Db::class => DbInterface::class,
        Cookie::class => CookieInterface::class,
        Session::class => SessionInterface::class,
        Setting::class => SettingInterface::class,
        Router::class => RouterInterface::class,
        Log::class => LogInterface::class,
        Path::class => PathInterface::class,
        Debug::class => DebugInterface::class,
        Dto::class => DtoInterface::class,
        Cache::class => CacheInterface::class,
        Redirect::class => RedirectInterface::class,
        System::class => SystemInterface::class,
        Template::class => TemplateInterface::class,
        Command::class => CommandInterface::class,
        DI::class => DiInterface::class,
    ];

    protected static array $singletons = [];

    protected static ?array $customServiceKeys = null;

    final public static function getCustomKeys(): ?array
    {
        return self::$customServiceKeys;
    }

    protected static function setSingleton(string $id, object|callable|null $value): void
    {
        self::$singletons[$id] = $value;
        if (\is_null(self::$customServiceKeys)) {
            return;
        }
        if (\is_null($value)) {
            unset(self::$customServiceKeys[$id]);
        } else {
            self::$customServiceKeys[] = $id;
        }
    }

    final protected static function register(string $id): void
    {
        if (\is_null(self::$customServiceKeys)) {
            self::$customServiceKeys = [];
        } else if (isset(self::SERVICE_MAP[$id]) || \in_array($id, self::SERVICE_MAP)) {
            return;
        }
        self::$customServiceKeys[$id] ?? self::$customServiceKeys[] = $id;
    }

    final protected static function has(string &$id): bool
    {
        $id = self::SERVICE_MAP[$id] ?? $id;

        return \array_key_exists($id, self::$singletons);
    }

}
