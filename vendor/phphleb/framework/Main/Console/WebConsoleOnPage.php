<?php


namespace Hleb\Main\Console;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\CoreException;
use Hleb\Static\Response;

#[Accessible]
final class WebConsoleOnPage extends WebConsole
{

    public function run(): void
    {
        Response::addHeaders(['Content-Type' => 'text/html; charset=utf-8']);
        ob_start();
        $console = '';
        $result = $this->load();
        $arguments = $this->getArgs();
        $arguments[] = '--strict-verbosity';
        $result and $console = (new ConsoleHandler($arguments))->run();
        $content = ob_get_clean();
        Response::addToBody($content . $this->addFooter($console));
    }
}
