<?php

namespace Hleb\Main\Console\Extreme;

use Hleb\Constructor\Data\SystemSettings;
use Hleb\Static\Session;

readonly class ExtremeDataTransfer
{

    public static function checkCommand(string $command): bool
    {
        return \str_starts_with(\trim($command), 'php console');
    }

    public function run(array $data = []): void
    {
        if (isset($data['command'])) {
            if (SystemSettings::isAsync()) {
                Session::set('command', $data['command']);
            } else {
                $_SESSION['command'] = $data['command'];
                \session_commit();
            }
            ExtremeRequest::redirect(ExtremeRequest::getUri());
        }
    }

    public function convertCommand(): array
    {
        $params = $_SESSION['command'] ?? Session::get('command') ?: null;
        if (!empty($params)) {
            $cmd = \preg_replace('/\s+/',' ', (string)$params);
            $parts = \explode(' ', \trim($cmd));

            \array_shift($parts);
            if (self::checkCommand($cmd) && $cmd) {
                return $parts;
            }
        }

        return [];
    }

    public function singleGetCommand(): null|string
    {
        if (SystemSettings::isAsync()) {
            $params = Session::get('command');
            Session::set('command', null);
        } else {
            $params = $_SESSION['command'] ?? null;
            unset($_SESSION['command']);
        }

        return $params;
    }
}
