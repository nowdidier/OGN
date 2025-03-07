<?php


namespace Hleb\Constructor\DI;

use App\Bootstrap\BaseContainer;
use App\Bootstrap\ContainerInterface;
use Hleb\Constructor\Attributes\Autowiring\AllowAutowire;
use Hleb\Constructor\Attributes\Autowiring\NoAutowire;
use Hleb\Helpers\ArrayHelper;
use Hleb\Helpers\AttributeHelper;
use Hleb\Helpers\ReflectionMethod;
use Hleb\ReflectionProcessException;
use Hleb\Constructor\Attributes\Autowiring\DI;
use Hleb\UnexpectedValueException;

final class DependencyInjection
{
    private const EXCLUDED = ['bool', 'boolean', 'int', 'integer', 'float', 'double', 'string', 'array', 'object', 'callable', 'mixed', 'resource', 'null'];

    public static function prepare(ReflectionMethod $reflector, array $arguments = [], ?ContainerInterface $container = null): array
    {
        $result = [];
        if ($arguments && !ArrayHelper::isAssoc($arguments)) {
            throw new ReflectionProcessException("The array of wildcard elements must be associative.");
        }
        $defaults = $reflector->getArgDefaultValueList();
        $list = $reflector->getArgTypeList();
        $diAttributes = $reflector->searchAttributes(DI::class);

        foreach ($list as $name => $types) {


            if (\array_key_exists($name, $arguments)) {
                $result[$name] = $arguments[$name];
                continue;
            }



            $attribute = $diAttributes[$name] ?? null;
            if ($attribute) {
                $item = $attribute->classNameOrObject;
                if (\is_string($item)) {

                    $container = $container ?? BaseContainer::instance();
                    $item = $container->get($item);
                    if ($item === null){
                        $item = self::create($attribute->classNameOrObject, $container);
                    }
                }
                $result[$name] = $item;
                continue;
            }

            foreach ($types as $type) {
                if (\in_array(\strtolower($type), self::EXCLUDED)) {
                    continue;
                }
                $container = $container ?? BaseContainer::instance();
                $result[$name] = $container->get($type);
                if ($result[$name] !== null) {
                    continue 2;
                }


                try {
                    if (\class_exists($type)) {
                        $result[$name] = self::create($type, $container);
                    }
                } catch (\Throwable) {
                }
                if (!empty($result[$name])) {
                    continue 2;
                }

                if (\array_key_exists($name, $defaults)) {
                    $result[$name] = $defaults[$name];
                    continue 2;
                }
                $target = $reflector->getClassName() . ':' . $reflector->getMethodName();

                throw new ReflectionProcessException("No wildcard element found for $target parameter `$name`.");
            }
        }
        return $result;
    }

    private static function checkAutowiring(string|object $class, ?int $mode): bool
    {
        \is_object($class) and $class = $class::class;

        $isAllow = match ($mode) {
            1, 3 => false,
            0, 2, null => true,
            default => throw new UnexpectedValueException('Unsupported mode number'),
        };

        if ($mode === 2 || $mode === 3) {
            $attribute = $mode === 2 ? NoAutowire::class : AllowAutowire::class;
            if ((new AttributeHelper($class))->hasClassAttribute($attribute)) {
                $isAllow = !$isAllow;
            }
        }
        return $isAllow;
    }

    private static function create(string|object $class, ContainerInterface $container): ?object
    {
        if (self::checkAutowiring($class, $container->settings()->getParam('system', 'autowiring.mode'))) {
            if (\method_exists($class, '__construct')) {
                $ref = new ReflectionMethod($class, '__construct');
                return new $class(...($ref->countArgs() ? self::prepare($ref) : []));
            } else {
                return new $class();
            }
        }
        return null;
    }
}
