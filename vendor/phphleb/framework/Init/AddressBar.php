<?php


namespace Hleb\Init;

use Hleb\HttpMethods\External\SystemRequest;
use Hleb\HttpMethods\External\RequestUri;

final class AddressBar
{
    private array $config;
    private ?SystemRequest $request;
    private ?string $originUrl;
    private false|string $resultUrl;
    private ?RequestUri $uri;

    public function __construct()
    {
        $this->config = [];
        $this->request = null;
        $this->uri = null;
        $this->originUrl = null;
        $this->resultUrl = false;
    }

    public function init(array $config, SystemRequest $request): void
    {
        $this->config = $config;
        $this->request = $request;
        $this->uri = $request->getUri();
        $this->originUrl = $this->uri->getScheme() . '://' . $this->uri->getHost() . $this->uri->getPath() . $this->uri->getQuery();
    }

    public function check(): AddressBar
    {
        $validateUrl = $this->config['system']['url.validation'];
        $endingUrl = $this->config['system']['ending.slash.url'];
        $urlPath = $this->uri->getPath();
        $method = $this->request->getMethod();
        $methods = $this->config['system']['ending.url.methods'];


        (\in_array(\strtolower($method), $methods, true) || \in_array($method, $methods, true)) or $endingUrl = false;


        $urlPath = \str_contains($urlPath, '//') ? \preg_replace('!/+!', '/', $urlPath) : $urlPath;


        $endingUrl === false or $urlPath = \rtrim($urlPath, '/');


        ($endingUrl === 1 || $endingUrl === '1') and $urlPath .= '/';


        ($this->uri->getQuery() !== '' && $urlPath === '') and $urlPath = \rtrim($urlPath, '/') . '/';


        $urlPath === '' and $urlPath = '/';


        if ($validateUrl && !\preg_match($validateUrl, $urlPath)) {
            $this->resultUrl = $this->uri->getScheme() . '://' . $this->uri->getHost();
        } else {
            $this->resultUrl = $this->uri->getScheme() . '://' . $this->uri->getHost() . $urlPath . $this->uri->getQuery();
        }

        return $this;
    }

    public function isUrlCompare(): bool
    {
        return $this->resultUrl === $this->originUrl;
    }

    public function getResultUrl(): false|string
    {
        return $this->resultUrl;
    }

    public function getOriginUrl(): string
    {
        return $this->originUrl;
    }
}
