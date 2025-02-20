<?php


namespace Hleb\Reference;

use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\Main\Insert\ContainerUniqueItem;

#[Accessible] #[AvailableAsParent]
class CsrfReference extends ContainerUniqueItem implements CsrfInterface, Interface\Csrf, RollbackInterface
{

    #[\Override]
    public function token(): string
    {
        return \Hleb\Constructor\Protected\Csrf::key();
    }

    #[\Override]
    public function field(): string
    {
        return '<input type="hidden" name="_token" value="' . $this->token() . '">';
    }

    #[\Override]
    public function validate(?string $key): bool
    {
        return \Hleb\Constructor\Protected\Csrf::validate($key);
    }

    #[\Override]
    public function discover(): string|null
    {
        return \Hleb\Constructor\Protected\Csrf::discover();
    }

    #[\Override]
    public static function rollback(): void
    {
        \Hleb\Constructor\Protected\Csrf::rollback();
    }
}
