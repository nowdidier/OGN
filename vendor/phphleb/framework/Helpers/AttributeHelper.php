<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\DynamicStateException;

#[Accessible]
final class AttributeHelper
{
    private static array $cacheRefClass = [];

    private static array $cacheRefMethod = [];

    public function __construct(private readonly string $className)
    {
    }

    public function getFromClass(): array
    {
        if (\array_key_exists($this->className, self::$cacheRefClass)) {
            return self::$cacheRefClass[$this->className];
        }
        $result = [];
        foreach ($this->getRefClass()->getAttributes() as $attribute) {
            $result[$attribute->getName()] = $attribute->newInstance();
        }

        return self::$cacheRefClass[$this->className] = $result;
    }

    public function getFromMethod(string $methodName): array
    {
        $tag = $this->className . ':' . $methodName;
        if (array_key_exists($tag, self::$cacheRefMethod)) {
            return self::$cacheRefMethod[$tag];
        }
        $refMethod = $this->getRefMethod($methodName);
        foreach ($refMethod->getAttributes() as $attribute) {
            if ($attribute->getName() !== 'Override') {
                self::$cacheRefMethod[$tag][$attribute->getName()] = $attribute->newInstance();
            }
        }

        return self::$cacheRefMethod[$tag];
    }

    public function hasClassAttribute(string $name): bool
    {
        return \array_key_exists($name, $this->getFromClass());
    }

    public function getClassValue(string $attribute, string $name): mixed
    {
        $attributes = $this->getFromClass();
        if (\array_key_exists($attribute, $attributes) && isset($attributes[$attribute]?->$name)) {
            return $attributes[$attribute]->$name;
        }
        return null;
    }

    public function hasMethodAttribute(string $method, string $name): bool
    {
        return \array_key_exists($name, $this->getFromMethod($method));
    }

    public function getMethodValue(string $method, string $attribute, string $name): mixed
    {
        $attributes = $this->getFromMethod($method);
        if (\array_key_exists($attribute, $attributes) && isset($attributes[$attribute]?->$name)) {
            return $attributes[$attribute]->$name;
        }
        return null;
    }

    private function getRefClass(): \ReflectionClass
    {
        try {
            return new \ReflectionClass($this->className);
        } catch (\ReflectionException $e) {
            throw new DynamicStateException($e);
        }
    }

    private function getRefMethod(string $methodName): \ReflectionMethod
    {
        try {
            return $this->getRefClass()->getMethod($methodName);
        } catch (\ReflectionException $e) {
            throw new DynamicStateException($e);
        }
    }
}
