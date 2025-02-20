<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;

#[Accessible]
final class ClassDataInFile
{
    private array|null $data = null;

    private string|null $content;

    public function __construct(string $file)
    {
        $this->content = \file_get_contents($file);
    }

    public function getClass(): string|false
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));
        if ($this->isClass()) {
            [$namespace, $class,] = $this->data;
            if ($namespace) {
                $namespace = \rtrim($namespace, '\\') . '\\';
            }
            return $namespace . \rtrim($class, '\\');
        }
        return false;
    }

    public function getNamespace(): string|false
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));

        if ($this->isClass()) {
            return \current($this->data);
        }
        return false;
    }

    public function isClass(): bool
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));

        return \count($this->data) === 3;

    }

    public function isStandardClass(): bool
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));

        return \count($this->data) && end($this->data) === T_CLASS;

    }

    public function isTrait(): bool
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));

        return \count($this->data) && end($this->data) === T_TRAIT;

    }

    public function isInterface(): bool
    {
        $this->data === null and $this->data = $this->parse(\token_get_all($this->content));

        return \count($this->data) && end($this->data) === T_INTERFACE;

    }

    private function parse(array $tokens): array
    {
        $lastLine = 1;
        $prevNum = 0;
        $namespace = '';
        foreach ($tokens as $token) {


            if (!\is_array($token) || \count($token) < 3) {
               continue;
            }
            [$num, $str, $line] = $token;
            if ($num === T_WHITESPACE || $num === T_LOGICAL_OR) {
                continue;
            }
            if ($num === T_CLASS && \in_array($prevNum, [T_DOUBLE_COLON, T_NEW])) {
                continue;
            }
            if ($line !== $lastLine) {
                $prevNum = 0;
                $lastLine = $line;
            }
            if ($prevNum === T_NAMESPACE) {
                $namespace = $str;
                continue;
            }
            if (\in_array($prevNum, [T_CLASS, T_TRAIT, T_INTERFACE])) {
                $class = $str;
                return [$namespace, $class, $prevNum];
            }

            $prevNum = $num;
        }
        return [];
    }
}
