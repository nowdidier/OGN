<?php


namespace Hleb\Main\Console\Extreme;

use Hleb\Constructor\Data\SystemSettings;
use Hleb\Helpers\Abracadabra;
use Hleb\Static\Session;
use JetBrains\PhpStorm\NoReturn;

final class ExtremeIdentifier
{
    final public const KEY_PATH = 'storage/keys/web-console.key';

    final public const KEY_NAME = 'HLEB_WEB_CONSOLE';

    final public const KEY_ON = 'HLEB_WEB_CONSOLE_ON';

    private bool $isAsync = false;

    public function __construct(private readonly array $regData = [])
    {
        $this->isAsync = SystemSettings::isAsync();
    }

    public function advance(): bool
    {
        $double = false;
        if ($this->isAsync) {
            $double = (bool)Session::get(self::KEY_ON);
        }
        return !empty($_SESSION[self::KEY_ON]) || $double;
    }

    #[NoReturn] public function exit(): void
    {
        if ($this->isAsync) {
            Session::clear();
        } else {
            \session_destroy();
        }
        ExtremeRequest::redirect(ExtremeRequest::getUri());
    }

    public function verification(): bool
    {


        $regularUpdate = \mt_rand(0, 100) === 1;

        if (!$regularUpdate && $this->advance()) {
            return true;
        }
        $createKey = $this->getKeyOrCreate($regularUpdate);
        $key = \trim($this->regData[self::KEY_NAME] ?? '');
        if (!$key) {
            return false;
        }
        $check = $createKey === $key;

        if ($check) {
            if ($this->isAsync) {
                Session::set(self::KEY_ON, 1);
            }
            $_SESSION[self::KEY_ON] = 1;
        }

        return $check;
    }

    private function getKeyOrCreate(bool $force): string
    {
        $file = SystemSettings::getPath('@' . self::KEY_PATH);
        if (!\file_exists($file) || $force) {
            \file_put_contents($file, Abracadabra::generate(72));
        }
        $key = \file_get_contents($file);
        if (!$key) {
            throw new \RuntimeException("Failed to save key in " . self::KEY_PATH);
        }

        return $key;
    }
}
