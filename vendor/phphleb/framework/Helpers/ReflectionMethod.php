<?php


namespace Hleb\Helpers;

use Hleb\Constructor\Attributes\Accessible;
use Hleb\ReflectionProcessException;
use ReflectionException;
use ReflectionParameter;

#[Accessible]
final class ReflectionMethod
{
    private \ReflectionMethod $method;

    private array $params;

    private ?array $returnTypes = null;

    private ?array $defaultValuesList = null;

    private ?array $typeList = null;

    private ?array $nameList = null;

    public function __construct(
        private readonly string $className,
        private readonly string $methodName,
    )
    {
        try {
            $this->method = (new \ReflectionClass($className))->getMethod($methodName);
        } catch (\ReflectionException $e) {
            throw new ReflectionProcessException($e);
        }
        $this->params = $this->method->getParameters();
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getArgNameList(): array
    {
        if ($this->nameList !== null) {
            return $this->nameList;
        }
        $names = [];
        foreach ($this->params as $value) {
            $names[] = $value->getName();
        }

        return $this->nameList = $names;
    }

    public function getArgTypeList(): array
    {
        if ($this->typeList !== null) {
            return $this->typeList;
        }
        $methodList = [];

        foreach ($this->params as $value) {
            if (!$value) {
                continue;
            }

            $types = $value->getType();
            if (!$types) {
                continue;
            }
            $name = $value->getName();
            if (\method_exists($types, 'getTypes')) {
                foreach ($types->getTypes() as $type) {
                    $methodList[$name][] = $type->getName();
                }
            } else if (\method_exists($types, 'getType')) {
                $methodList[$name][] = $types->getType()->getName();
            } else {
                $methodList[$name][] = $types->getName();
            }
            if ($types->allowsNull()) {
                $methodList[$name][] = 'null';
            }
            $methodList[$name] = \array_unique($methodList[$name]);
        }
        return $this->typeList = $methodList;
    }

    public function countArgs(): int
    {
        return \count($this->params);
    }

    public function getDocComment(): string
    {
        $result = [];
        foreach(\preg_split("/\r\n|\r|\n/", (string)$this->method->getDocComment()) as $str) {
            $result[]= \trim(\ltrim($str, '/* '));
        }
        return \implode(PHP_EOL, $result);
    }

    public function getFirstLineInDocComment(): string
    {
        $doc = \explode(PHP_EOL, $this->getDocComment());

        return \trim($doc[0] ?: $doc[1] ?? '') . PHP_EOL;
    }

    public function getArgDefaultValueList(): array
    {
        if ($this->defaultValuesList !== null) {
            return $this->defaultValuesList;
        }
        $methodList = [];
        foreach ($this->params as $param) {
            if ($param->isOptional()) {
                try {
                    $methodList[$param->getName()] = $param->getDefaultValue();
                } catch(ReflectionException $e) {
                    throw new ReflectionProcessException($e);
                }
            }
        }

        return $this->defaultValuesList = $methodList;
    }

    public function getArgReturnTypesList(): array
    {
        if ($this->returnTypes !== null) {
            return $this->returnTypes;
        }
        $returnTypes = [];
        $types = $this->method->getReturnType();
        if (!$types) {
            return $this->returnTypes = [];
        }
        if (\method_exists($types, 'getTypes')) {
            $listTypes = $types->getTypes();
            foreach ($listTypes as $type) {
                $returnTypes[] = $type->getName();
            }
        } else {
            $returnTypes = [$types->getName()];
        }
        if ($types->allowsNull()) {
            $returnTypes[] = 'null';
        }

        return $this->returnTypes = \array_unique($returnTypes);
    }

    public function getErrorInArguments(array $data, array $favorites = []): false|array
    {
        $cells = [];
        $arguments = $this->getArgNameList();
        $default = $this->getArgDefaultValueList();
        foreach ($arguments as $arg) {
            $value = $data[$arg] ?? null;
            if ($value === null) {
                if (\array_key_exists($arg, $default)) {
                    continue;
                }
                if ($favorites && !in_array($arg, $favorites)) {
                    continue;
                }
                $cells[] = $arg;
            }
        }

        return \count($cells) ? $cells : false;
    }

    public function convertArguments(array $data, array $favorites = []): array|false
    {
        $result = [];
        $arguments = $this->getArgNameList();
        $types = $this->getArgTypeList();
        $default = $this->getArgDefaultValueList();
        foreach ($arguments as $arg) {
            $value = $data[$arg] ?? null;


            if ($value === null) {


                if (\array_key_exists($arg, $default)) {
                    $result[$arg] = $default[$arg];
                    continue;
                }
                if ($favorites && !in_array($arg, $favorites)) {
                    continue;
                }
                return false;
            }
            $value = (string)$value;


            if (isset($types[$arg])) {
                $t = $types[$arg];
                if (\is_numeric($value)) {
                    if (\str_contains($value, '.')) {
                        if (\in_array('double', $t, true) || \in_array('float', $t, true)) {
                            $result[$arg] = (float)$value;
                            continue;
                        }
                    } else if (\in_array('int', $t, true) || \in_array('integer', $t, true)) {
                        $result[$arg] = (int)$value;
                        continue;
                    }
                }
                if (\in_array('mixed', $t, true) || \in_array('string', $t, true)) {
                    $result[$arg] = $value;
                    continue;
                }
                return false;
            }

            if (\is_numeric($value)) {
                $result[$arg] = \str_contains($value, '.') ? (float)$value : (int)$value;
            } else {
                $result[$arg] = $value;
            }
        }
        return $result;
    }

    public function searchAttributes(string $class): array
    {
        $result = [];

        foreach ($this->params as $parameter) {
            $attribute = \current($parameter->getAttributes($class));
            if ($attribute) {
                $result[$parameter->getName()] = $attribute->newInstance();
            }
        }
        return $result;
    }

    public function searchAttributesWithDuplicates(string $class): array
    {
        $result = [];

        foreach ($this->params as $parameter) {
            foreach ($parameter->getAttributes($class) as $attribute) {
                $result[$parameter->getName()][] = $attribute->newInstance();
            }
        }
        return $result;
    }
}
