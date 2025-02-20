<?php


namespace Hleb\Init\Headers;

class ParsePsrHeaders
{
    public function update(mixed $headers): array
    {
        if (empty($headers)) {
            return [];
        }
        $headers = (array)$headers;

        foreach ($headers as $n => $i) {


            if ($i && \is_array($i)) {
                $headers[$n] = \trim(\implode(',', $i));
            }
        }
        foreach ($headers as $name => $header) {
            if (!\is_array($header)) {
                $items = [];
                $header = \trim((string)$header);
                foreach (\explode(',', $header) as $p) {
                    $r = \trim($p);
                    if (!\in_array($r, $items)) {
                        $items[] = $r;
                    }
                }
                $headers[$name] = $items;
            }
        }
        return $headers;
    }
}
