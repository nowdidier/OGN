<?php


namespace Hleb\Reference;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Constructor\Data\DynamicParams;
use Hleb\Constructor\Data\SystemSettings;
use Hleb\Main\Insert\ContainerUniqueItem;
use Hleb\Static\Request;
use Hleb\Static\Session;

#[Accessible] #[AvailableAsParent]
class SettingReference extends ContainerUniqueItem implements SettingInterface, Interface\Setting
{

    #[\Override]
    public function isStandardMode(): bool
    {
        return SystemSettings::isStandardMode();
    }

    #[\Override]
    public function isAsync(): bool
    {
        return SystemSettings::isAsync();
    }

    #[\Override]
    public function isCli(): bool
    {
        return SystemSettings::isCli();
    }

    #[\Override]
    public function isDebug(): bool
    {
        return DynamicParams::isDebug();
    }

    #[\Override]
    public function getRealPath(string $keyOrPath): false|string
    {
        return SystemSettings::getRealPath($keyOrPath);
    }

    #[\Override]
    public function getPath(string $keyOrPath): false|string
    {
        return SystemSettings::getPath($keyOrPath);
    }

    #[\Override]
    public function isEndingUrl(): bool
    {
        return DynamicParams::isEndingUrl();
    }

    #[\Override]
    public function getParam(string $name, string $key): mixed
    {
        return SystemSettings::getValue($name, $key);
    }

    #[\Override]
    public function common(string $key): mixed
    {
        return $this->getParam('common', $key);
    }

    #[\Override]
    public function main(string $key): mixed
    {
        return $this->getParam('main', $key);
    }

    #[\Override]
    public function database(string $key): mixed
    {
        return $this->getParam('database', $key);
    }

    #[\Override]
    public function system(string $key): mixed
    {
        return $this->getParam('system', $key);
    }

    #[\Override]
    public function getModuleName(): ?string
    {
        return DynamicParams::getModuleName();
    }

    #[\Override]
    public function getControllerMethodName(): ?string
    {
        return DynamicParams::getControllerMethodName();
    }

    #[\Override]
    public function getDefaultLang(): string
    {
        return SystemSettings::getValue('main', 'default.lang');
    }

    #[\Override]
    public function getAutodetectLang(): string
    {
        $allowed = self::getAllowedLanguages();

        $search = static function ($lang) use ($allowed): bool {
            return $lang && \in_array(\strtolower($lang), $allowed);
        };

        if ($search($lang = \explode('/', \trim(Request::getUri()->getPath(), '/'))[0])) {
            return $lang;
        }
        if ($search($lang = Request::param('lang')->value)) {
            return $lang;
        }
        if ($search($lang = Request::get('lang')->value)) {
            return $lang;
        }
        if ($search($lang = Request::post('lang')->value)) {
            return $lang;
        }
        if ($search($lang = Session::get('LANG'))) {
            return $lang;
        }

        return $this->getDefaultLang();
    }

    #[\Override]
    public function getAllowedLanguages(): array
    {
        return $this->getParam('main', 'allowed.languages');
    }

    #[\Override]
    public function getInitialRequest(): object
    {
        return DynamicParams::getDynamicOriginRequest();
    }
}
