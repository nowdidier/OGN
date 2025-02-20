<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\Static\Response;

#[Accessible]
final class ResourceViewHelper
{

    public function add(string $file, int $maxAge = 31536000): bool
    {
        $cache = ['Cache-Control' => 'public, max-age=' . $maxAge, 'Pragma' => 'cache'];

        if (\str_ends_with($file, '.js')) {
            Response::addHeaders(['Content-Type' => 'text/javascript; charset=utf-8'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }
        if (\str_ends_with($file, '.css')) {
            Response::addHeaders(['Content-Type' => 'text/css; charset=UTF-8'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }
        if (\str_ends_with($file, '.json')) {
            Response::addHeaders(['Content-Type' => 'application/json; charset=UTF-8'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }
        if (\str_ends_with($file, '.svg')) {
            Response::addHeaders(['Content-Type' => 'image/svg+xml; charset=UTF-8'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }
        if (\str_ends_with($file, '.gif')) {
            Response::addHeaders(['Content-Type' => 'image/gif; charset=UTF-8'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }
        if (\str_ends_with($file, '.woff2')) {
            Response::addHeaders(['Content-Type' => 'font/woff2'] + $cache);
            Response::setBody(\file_get_contents($file));
            return true;
        }

        return false;
    }
}
