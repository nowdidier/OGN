<?php


namespace Hleb\Main\Logger;

use Hleb\Constructor\Attributes\NotFinal;

#[NotFinal]
class BaseLogger
{
    protected string $logMessage = '';

    protected string $format = 'row';

    protected bool $isDebug = false;

    public function setFormat(string $value): static
    {
        $this->format = $value;

        return $this;
    }

    public function getLog(): string
    {
        return $this->logMessage;
    }

    protected function createLog(string $level, string $message, array $context): string
    {
        if ($this->format === 'row') {
            return $this->createStandardLog($level, $message, $context);
        }
        return $this->createJsonLog($level, $message, $context);
    }

    protected function createStandardLog(string $level, string $message, array $context): string
    {
        $timezone = $this->getTimezone();
        $type = !empty($context['is_queue']) ? 'Command:' : (!empty($context['is_console']) ? 'System:' : 'Web:');
        $log = [
            '[' . \date('H:i:s.') . (new \DateTime())->format('u') . \date(' d.m.Y') . " UTC$timezone]",
            $type . \strtoupper($level),
        ];

        $log[] = $message;
        if (isset($context['file'], $context['line'])) {
            $log[] = '{' . $context['file'] . ' on line ' . $context['line'] . '}';
        }
        if (isset($context['class'], $context['function'])) {
            $log[] = '{' . $context['class'] . ($context['method'] ?? ':') . $context['function'] . '()}';
        }
        if (isset($context['http_method'])) {
            $log[] = $context['http_method'];
        }
        $context['debug'] = $this->isDebug;
        if (isset($context['domain'], $context['url'])) {
            $scheme = isset($context['scheme']) ? $context['scheme'] . '://' : '';
            $query = !empty($context['query']) ? $context['query'] : '';
            $log[] = $scheme . $context['domain'] . ($context['url'] !== '/' ? $context['url'] : '') . $query;
        }
        if (isset($context['ip'])) {
            $log[] = $context['ip'];
        }
        $replace = [];
        foreach ($context as $key => $val) {
            if ((\is_string($val) || \is_numeric($val)) && \str_contains($message, '{' . $key . '}')) {
                $replace['{' . $key . '}'] = $val;
                unset($context[$key]);
            }
        }
        $log[2] = strtr($message, $replace);
        unset($context['class'], $context['function'], $context['type'], $context['method'], $context['is_console'],
            $context['ip'], $context['file'], $context['line'], $context['http_method'], $context['is_queue'],
            $context['domain'], $context['url'], $context['scheme'], $context['query']
        );
        !empty($context['request-id']) or $context['request-id'] = \sha1(\microtime() . \rand());
        try {
            $row = \implode(' ', $log) . ' #' . \json_encode($context, JSON_THROW_ON_ERROR);
        } catch(\JsonException $e) {
            $row = \implode(' ', $log) . '#{"error":"' . $e->getMessage() . '"}';
        }
        return \str_replace([PHP_EOL, "\r", "\n"], ' ', $row);
    }

    protected function createJsonLog(string $level, string $message, array $context): string
    {
        $base = [
            'log' => 'framework',
            'level' => $level,
            'datetime' => \date('H:i:s d.m.Y'),
            'ms' => (new \DateTime())->format('u'),
            'timestamp' => \time(),
            'timezone' => $this->getTimezone(),
            'date' => \date('Y.m.d'),
            'time' => \date('H:i:s'),
        ];
        $body = [
            'message' => $message,
            'request-id' => $context['request-id'] ?? \sha1(\microtime() . \rand()),
            'method' => $context['http_method'] ?? null,
            'ip' => $context['ip'] ?? null,
            'cli' => (bool)($context['is_console'] ?? false),
            'domain' => $context['domain'] ?? null,
            'debug' => $this->isDebug,
        ];
        $body = \array_merge($base, $body);
        unset($context['request-id'], $context['ip'], $context['is_console'], $context['http_method'], $context['domain'], $context['method']);
        try {
            return \json_encode(\array_merge($body, ['context' => \array_filter($context)]), JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            try {
                return \json_encode(\array_merge($body, ['context' => ['parse_error' => $e->getMessage()]]), JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                return \json_encode(\array_merge($base, ['message' => 'Failed to process message']));
            }
        }
    }

    protected function getTimezone(): string
    {
        $timezone = \str_replace(['+00:00', '-00:00', '00:00', ':00'], '', \date('P'));
        $timezone !== '' or $timezone = '+00';
        return (string)$timezone;
    }
}
