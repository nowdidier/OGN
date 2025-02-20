<?php


namespace Hleb\Constructor\Templates;

use App\Bootstrap\ContainerInterface;
use Hleb\CoreProcessException;
use Hleb\Helpers\DirectoryInspector;
use Hleb\Static\Request;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

final readonly class TwigTemplate implements TemplateInterface
{
    public function __construct(
        private string $path,
        private array $data,
        private array $twigLoader,
        private array $twigOptions,
        private string $cachePath,
        private string $rootDirectory,
        private array $invertedDirectories,
        private string $realPath,
        private ContainerInterface $container,
    )
    {
    }

    #[\Override]
    public function view(): void
    {
        $data = \array_merge($this->data, $this->data());
        if (\class_exists('Twig\Loader\FilesystemLoader') && \class_exists('Twig\Environment')) {
            $options = $this->twigOptions;

            if (\count($this->invertedDirectories)) {
                $options['cache'] = $this->getCachingState(
                    $this->realPath,
                    $this->rootDirectory,
                    $this->invertedDirectories,
                    (bool)$options['cache']
                ) ? $this->cachePath : false;
            } else {
                $options['cache'] = $options['cache'] ? $this->cachePath : false;
            }

            $loader = new FilesystemLoader(\array_values($this->twigLoader));
            foreach ($this->twigLoader as $name => $path) {
                try {
                    $loader->addPath($path, $name);
                } catch (LoaderError $e) {
                    throw new CoreProcessException($e->getMessage());
                }
            }
            $twig = new Environment($loader, $options);
            try {
                echo $twig->render($this->path, $data);
            } catch(\Exception $e) {
                throw new CoreProcessException($e->getMessage());
            }
        } else {
            throw new CoreProcessException('Undefined Twig library');
        }
    }

    private function getCachingState(string $dirOrFile, string $projectRootDir, array $invertedDirs, bool $isCache): bool
    {
        $helper = new DirectoryInspector();
        $relativeDir = $helper->getRelativeDirectory($projectRootDir, \dirname($dirOrFile));

        if ($relativeDir === false) {
            throw new CoreProcessException('Wrong folder name for Twig cache');
        }
        $searchDir = $helper->isDirectoryEntry($relativeDir, $invertedDirs);

        return ($isCache && !$searchDir) || (!$isCache && $searchDir);
    }

    private function data(): array
    {
        $req = Request::getUri();
        $address = Request::getAddress();
        $scheme = $req->getScheme();
        $path = $req->getPath();
        $host = $req->getHost();
        $query = $req->getQuery();
        $container = $this->container;

        return [
            'app_request_uri' => $path,
            'app_request_path' => $path,
            'app_request_pathAndQuery' => $path . $query,
            'app_request_schemeAndHttpHost' => $scheme . '://' . $host,
            'app_request_scheme' => $scheme,
            'app_request_httpHost' => $host,
            'app_request_host' => $host,
            'app_request_url' => $address,
            'app_request_urlAndQuery' => $address . $query,
            'container' => $container,
        ];
    }
}
