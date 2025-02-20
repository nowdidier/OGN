<?php


namespace Hleb\Constructor\Templates;

use App\Bootstrap\ContainerInterface;
use Hleb\Constructor\Data\SystemSettings;

final class Template
{
    final public const TWIG = 'twig';

    final public const PHP = 'php';

    private ?string $path = null;

    private ?string $realPath = null;

    private ?string $rootPath = null;

    private ?string $cachePath = null;

    private ?ContainerInterface $container = null;

    private array $viewPaths =  [];

    private array $invertedPaths = [];

    private array $data = [];

    public function __construct(readonly private string $type)
    {
    }

    public function view(): void
    {
        match ($this->type) {
            self::TWIG => (new TwigTemplate(
                $this->path,
                $this->data,
                $this->viewPaths,
                SystemSettings::getValue('common', 'twig.options'),
                $this->cachePath,
                $this->rootPath,
                $this->invertedPaths,
                $this->realPath,
                $this->container,
            ))->view(),
            self::PHP => (new PhpTemplate(
                $this->path,
                $this->data,
            ))->view(),
        };
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function setContainer(ContainerInterface $container): static
    {
        $this->container = $container;

        return $this;
    }

    public function setRealPath(string $path): static
    {
        $this->realPath = $path;

        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function setCachePath(string $path): static
    {
        $this->cachePath = $path;

        return $this;
    }

    public function setViewPaths(array $paths): static
    {
        $this->viewPaths = $paths;

        return $this;
    }

    public function setRootPath(string $path): static
    {
        $this->rootPath = $path;

        return $this;
    }

    public function setInvertedPath(array $paths): static
    {
        $this->invertedPaths = $paths;

        return $this;
    }
}
