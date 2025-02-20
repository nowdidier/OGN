<?php


declare(strict_types=1);

namespace Hleb\Base;

use App\Bootstrap\BaseContainer;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Reference\SettingInterface;
use Hleb\Main\Console\{Colorizer, Console, Specifiers\ArgType, Specifiers\LightDataType};

#[AvailableAsParent]
abstract class Task extends Console
{

    #[\Override]
    final protected function settings(): SettingInterface
    {
        return parent::settings();
    }

    #[\Override]
    final public function call(array $arguments = [], ?bool $strictVerbosity = null): bool
    {
        return parent::call($arguments, $strictVerbosity);
    }

    #[\Override]
    final public function getCode(): int
    {
        return parent::getCode();
    }

    #[\Override]
    final public function getResult(): mixed
    {
        return parent::getResult();
    }

    #[\Override]
    final protected function getOptions(): array
    {
        return parent::getOptions();
    }

    #[\Override]
    final protected function getOption(string $name): ?LightDataType
    {
        return parent::getOption($name);
    }

    protected function rules(): array
    {
        return [];
    }

    protected function color(): Colorizer
    {
        return parent::color();
    }
}
