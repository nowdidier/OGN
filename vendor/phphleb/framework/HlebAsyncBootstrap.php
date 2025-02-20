<?php


declare(strict_types=1);

namespace Hleb;

use App\Bootstrap\ContainerFactory;
use App\Middlewares\Hlogin\Registrar;
use AsyncExitException;
use Exception;
use Hleb\Base\RollbackInterface;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\AvailableAsParent;
use Hleb\HttpMethods\{External\RequestUri, External\SystemRequest};
use Hleb\Constructor\Data\DebugAnalytics;
use Hleb\HttpMethods\External\Response as SystemResponse;
use Hleb\HttpMethods\Intelligence\Cookies\AsyncCookies;
use Hleb\Init\ErrorLog;
use Hleb\Init\Headers\ParsePsrHeaders;
use Hleb\Init\Headers\ParseSwooleHeaders;
use Hleb\Main\Logger\LoggerInterface;
use Hleb\Static\Response;
use Throwable;

#[Accessible] #[AvailableAsParent]
class HlebAsyncBootstrap extends HlebBootstrap
{
    private static int $processNumber = 0;

    public function __construct(?string $publicPath = null, array $config = [], ?LoggerInterface $logger = null)
    {
        $this->mode = self::ASYNC_MODE;


        try {
            parent::__construct($publicPath, $config, $logger);
        } catch (\Throwable $t) {
            $this->errorLog($t);
            throw $t;
        }
    }

    #[\Override]
    public function setLogger(LoggerInterface $logger): static
    {
        parent::setLogger($logger);

        return $this;
    }

    #[\Override]
    public function load(?object $request = null, ?array $session = null, ?array $cookie = null): HlebAsyncBootstrap
    {
        $this->session = $session;
        $this->cookies = $cookie;

        self::$processNumber++;

        \ob_start();
        try {
            try {
                $this->loadProject($request);

                $this->requestCompletion((string)\ob_get_contents());

            } catch (AsyncExitException $e) {
                $this->asyncScriptExitEmulation($e, (string)\ob_get_contents());

            } catch (HttpException $e) {
                $this->scriptHttpError($e);

            } catch (Throwable $t) {
                $this->getPreviousErrorControl($t) or $this->scriptErrorHandling($t);
            }
        } catch (\Throwable) {

        }
        \ob_end_clean();

        $this->logsPostProcessing();

        $this->afterRequest();

        return $this;
    }

    public function afterRequest(): void
    {
        try {
            if (\class_exists(Response::class, false)) {
                $this->response = Response::getInstance() ?? new SystemResponse();
            }
            if (\session_status() === PHP_SESSION_ACTIVE) {
                AsyncCookies::setSessionName(\session_name());
                \session_write_close();
                \session_abort();
            }
            AsyncCookies::output();

        } catch (\Throwable) {
            if (\session_status() === PHP_SESSION_ACTIVE) {
                \session_abort();
            }
        }

        $_GET = $_POST = $_SERVER = $_SESSION = $_COOKIE = $_REQUEST = $_FILES = [];

        self::prepareAsyncRequestData($this->config, self::$processNumber);
    }

    public function getResponse(): SystemResponse
    {
        return $this->response;
    }

    public function getSession(): ?array
    {
        return $this->session;
    }

    public function getCookies(): ?array
    {
        return $this->cookies;
    }

    public function errorLog(\Throwable $e): void
    {


        try {
            \class_exists(ErrorLog::class, false) or require __DIR__ . '/Init/ErrorLog.php';
            ErrorLog::log($e);
        } catch (\Throwable $t) {
            \error_log((string)$e);
            \error_log((string)$t);
        }
    }

    protected static function prepareAsyncRequestData(array $config, int $processNumber): void
    {


        if ($config['system']['async.clear.state'] ?? true) {
            foreach (\get_declared_classes() as $class) {
                \is_a($class, RollbackInterface::class, true) and $class::rollback();
            }
        }
        foreach ([ContainerFactory::class, Registrar::class, DebugAnalytics::class, ErrorLog::class] as $class) {
            \class_exists($class, false) and $class::rollback();
        }

        $rate = (int)get_env('HLEB_ASYNC_RE_CLEANING', get_constant('HLEB_ASYNC_RE_CLEANING', self::DEFAULT_RE_CLEANING));
        if ($rate >= 0 && ($rate === 0 || $processNumber % $rate == 0)) {
            \gc_collect_cycles();
            \gc_mem_caches();
        }
        \memory_reset_peak_usage();
    }

    #[\Override]
    protected function buildRequest(?object $request = null): SystemRequest
    {
        $headers = [];
        if ($request !== null) {
            if (\method_exists($request, 'getCookieParams')) {
                [$body, $headers] = $this->parsePsr7Request($request);
                $headers = $this->parseHeaders($headers, ParsePsrHeaders::class);
            } else if (\method_exists($request, 'rawContent') ||
                \method_exists($request, 'getContent')
            ) {
                [$body, $headers] = $this->parseSwooleRequest($request);
                $headers = $this->parseHeaders($headers, ParseSwooleHeaders::class);
            } else if (\str_starts_with($request::class, "Workerman\\")) {
                [$body, $headers] = $this->parseWorkermanRequest($request);
            } else {


                $body = null;
            }
        }

        $_SERVER['HTTP_HOST'] = $this->convertHost($_SERVER['HTTP_HOST']);

        $this->standardization();
        $protocol = \trim(\stristr($_SERVER["SERVER_PROTOCOL"] ?? '','/') ?: '', ' /') ?: '1.1';

        $streamBody = isset($body) && \is_object($body) ? $body : null;
        $rawBody    = isset($body) && \is_string($body) ? $body : null;
        $parsedBody = isset($body) && \is_array($body)  ? $body : null;

        $_SERVER['REMOTE_ADDR'] = \strip_tags((string)($_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null));

        return new SystemRequest(
            (array)$_COOKIE,
            $rawBody,
            $parsedBody,
            $streamBody,
            $_SERVER['REQUEST_METHOD'],
            $headers,
            $protocol,
            new RequestUri(
                (string)$_SERVER['HTTP_HOST'],
                (string)$_SERVER['DOCUMENT_URI'],
                $_SERVER['QUERY_STRING'],
                $_SERVER['SERVER_PORT'],
                $_SERVER['REQUEST_SCHEME'],
                $_SERVER['REMOTE_ADDR'],
            ));
    }

    protected function parsePsr7Request(object $req): array
    {
        empty($_COOKIE) and $_COOKIE = $req->getCookieParams();
        empty($_POST) and $_POST = (array)$req->getParsedBody();
        $body = method_exists($req, 'getBody') ? (string)$req->getBody() : '';
        empty($_GET) and $_GET = (array)$req->getQueryParams();
        empty($_FILES) and $_FILES = $req->getUploadedFiles();
        isset($_SERVER['REQUEST_METHOD']) or $_SERVER['REQUEST_METHOD'] = \strtoupper((string)$req->getMethod());
        $headers = $req->getHeaders();
        if (\method_exists($req, 'getProtocolVersion')) {
            $_SERVER["SERVER_PROTOCOL"] = 'HTTP/' . $req->getProtocolVersion();
        }

        if (\method_exists($req, 'getUri') && \is_object($req->getUri())) {

            $uri = $req->getUri();
            isset($_SERVER['HTTP_HOST']) or $_SERVER['HTTP_HOST'] = $uri->getHost();
            isset($_SERVER['DOCUMENT_URI']) or $_SERVER['DOCUMENT_URI'] = $uri->getPath();
            isset($_SERVER['SERVER_NAME']) or $_SERVER['SERVER_NAME'] = $uri->getHost();
            isset($_SERVER['QUERY_STRING']) or $_SERVER['QUERY_STRING'] = $uri->getQuery();
            isset($_SERVER['SERVER_PORT']) or $_SERVER['SERVER_PORT'] = $uri->getPort();
            isset($_SERVER['REQUEST_URI']) or $_SERVER['REQUEST_URI'] = $uri->getPath() . '?' .
                \ltrim($uri->getQuery(), '?/');
            if (empty($_SERVER['REMOTE_ADDR'])) {
                if (\method_exists($req, 'getServerParams')) {
                    $params = $uri->getServerParams();
                    $_SERVER['REMOTE_ADDR'] = (string)($params['REMOTE_ADDR'] ?? $params['HTTP_CLIENT_IP'] ?? $params['HTTP_X_FORWARDED_FOR'] ?? null);
                } else if (\filter_var($_SERVER['HTTP_HOST'], FILTER_VALIDATE_IP)) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_HOST'];
                }
            }
        }
        return [$body, $headers];
    }

    protected function parseSwooleRequest(object $req): array
    {
        $headers = $req->header;
        $server = $req->server;
        $_COOKIE = $req->cookie ?? [];
        $_POST = $req->post ?? [];
        $_GET = $req->get ?? [];
        $_FILES = $req->files ?? [];
        $_SERVER['HTTP_HOST'] = $server['remote_addr'] ?? $headers['host'];
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['REMOTE_ADDR'] = $server['remote_addr'] ?? '';
        $_SERVER['REQUEST_METHOD'] = \strtoupper((string)$server['request_method']);
        $_SERVER['DOCUMENT_URI'] = $server['path_info'] ?? '';
        $_SERVER['SERVER_PORT'] = $server['server_port'] ?? null;
        $_SERVER['QUERY_STRING'] = $server['query_string'] ?? '';
        $_SERVER['REQUEST_URI'] = $server['request_uri'] ?? '';
        $_SERVER["SERVER_PROTOCOL"] = $server['server_protocol'] ?? 'HTTP/1.1';

        $_SERVER['HTTPS'] = $_SERVER['SERVER_PORT'] == 443 ? 'on' : 'off';


        if (isset($server['https'])) {
            $_SERVER['HTTPS'] = $server['https'] === 'on' ? 'on' : 'off';
        }
        $body = method_exists($req, 'rawContent') ? $req->rawContent() : $req->getContent();

        return [(string)$body, $headers];
    }

    protected function parseWorkermanRequest(object $req): array
    {


        $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? $req->host(true);
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
        $_SERVER['REMOTE_ADDR'] = $req->connection?->getRemoteIp();
        $get = $req->get() ?: [];
        $_GET = $get;
        $_POST = $req->post() ?: [];
        $_SERVER['QUERY_STRING'] = $get ? '?' . \http_build_query($get) : '';
        $_SERVER['REQUEST_METHOD'] = \strtoupper((string)$req->method());
        $_SERVER['DOCUMENT_URI'] = $req->uri() ?: '';
        $_SERVER['SERVER_PORT'] = $_SERVER['SERVER_PORT'] ?? $req->connection?->getLocalPort();
        $_SERVER['REQUEST_URI'] = $_SERVER['DOCUMENT_URI'] . $_SERVER['QUERY_STRING'];
        $_SERVER['HTTPS'] = $_SERVER['HTTPS'] ?? ($_SERVER['SERVER_PORT'] == 443 ? 'on' : 'off');
        $_SERVER["SERVER_PROTOCOL"] = 'HTTP/' . $req->protocolVersion();

        $body = $req->rawBody();
        $headers = $req->header() ?: [];
        $_SESSION = $req->session() ? $req->session()->all() : [];
        $_COOKIE = $req->cookie() ?: [];
        $_FILES = $req->file() ?: [];

        return [$body, $headers];
    }

    protected function parseHeaders(mixed $headers, string $class): array
    {
        $headers = (new $class())->update($headers);

        return \array_change_key_case($headers, CASE_LOWER);
    }

    private function asyncScriptExitEmulation(AsyncExitException $e, string $content): void
    {
        $this->output($content . $e->getMessage(), $e->getStatus(), $e->getHeaders());
    }
}
