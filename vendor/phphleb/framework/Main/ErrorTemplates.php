<?php


namespace Hleb\Main;

use Hleb\Http401UnauthorizedException;
use Hleb\Http403ForbiddenException;
use Hleb\Http404NotFoundException;

final readonly class ErrorTemplates
{
    public function __construct(private string|int $template)
    {
    }

    public function searchAndThrowError(): void
    {
        $error = match ($this->template) {
            '404', 404 => new Http404NotFoundException(),
            '403', 403 => new Http403ForbiddenException(),
            '401', 401 => new Http401UnauthorizedException(),
            default => null,
        };
        if ($error) {
            throw $error;
        }
    }
}
