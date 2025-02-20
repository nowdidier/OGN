<?php


namespace Hleb\Main\Console;

use App\Bootstrap\BaseContainer;
use App\Bootstrap\ContainerInterface;
use App\Bootstrap\Events\TaskEvent;
use Hleb\Constructor\Attributes\Disabled;
use Hleb\Constructor\Attributes\Task\Purpose;
use Hleb\Constructor\DI\DependencyInjection;
use Hleb\DomainException;
use Hleb\Helpers\AttributeHelper;
use Hleb\InvalidArgumentException;
use Hleb\Reference\SettingInterface;
use Hleb\DynamicStateException;
use Hleb\Helpers\ReflectionMethod;
use Hleb\Main\Console\Specifiers\LightDataType;
use Hleb\Static\Settings;

abstract class Console
{

    protected const ERROR_CODE = 1;

    protected const SUCCESS_CODE = 0;

    private const RUN_ERROR = 'Parameters for `run` method arguments are incorrect: ';

    private const RULE_ERROR = 'Parameters passed incorrectly according to the rules from the `rules` method: ';

    private int $code = 0;

    private mixed $result = null;

    private mixed $execResult = null;

    private bool $verbosity = true;

    private ?bool $strictVerbosity = null;

    private array $arguments = [];

    private array $basicArguments = [];

    private array $unnamedArguments = [];

    private bool $fromCli;

    private ?Colorizer $colorizer = null;

    private AttributeHelper $attributeHelper;

    protected readonly array $config;

    protected readonly ContainerInterface $container;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->container = $config['container'] ?? BaseContainer::instance();


        $this->fromCli = $this->settings()->isCli();

        $this->attributeHelper = new AttributeHelper(static::class);
    }

    public function call(array $arguments = [], ?bool $strictVerbosity = null): bool
    {
        $this->code = 0;
        $this->verbosity = true;
        $this->strictVerbosity = $strictVerbosity;
        $this->result = null;
        $this->execResult = null;
        $this->unnamedArguments = [];
        $this->arguments = [];
        $this->basicArguments = $arguments;

        if (!\method_exists(static::class, 'run')) {
            throw new DynamicStateException('Missing required `run` method for ' . static::class);
        }
        if ($this->attributeHelper->hasClassAttribute(Disabled::class)) {
            throw new DomainException('Execution is disabled by the presence of the #[Disabled] attribute.');
        }
        if (!$this->checkAttributes()) {
            throw new DynamicStateException('Forbidden by an attribute for a class ' . static::class);
        }

        if (Settings::system('events.used') !== false) {
            $eventMethod = new ReflectionMethod(TaskEvent::class, '__construct');
            $event = new TaskEvent(...($eventMethod->countArgs() > 1 ? DependencyInjection::prepare($eventMethod) : []));
            if (\method_exists($event, 'before')) {
                $this->unnamedArguments = $event->before(static::class, $this->fromCli ? 'run' : 'call', $this->unnamedArguments);
            }
        }

        $result = $this->fromCli ? $this->runCli() : $this->runOthers();
        if (\method_exists($event, 'after')) {
            $event->after(static::class, $this->fromCli ? 'run' : 'call', $this->result);
        }
        if (\method_exists($event, 'statusCode')) {
            $this->code = $event->statusCode(static::class, $this->fromCli ? 'run' : 'call', $this->code);
        }

        return $result;
    }

    final public function isAllowed(): bool
    {
        if ($this->attributeHelper->hasClassAttribute(Disabled::class)) {
            return false;
        }
        return $this->checkAttributes();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    final public function getExecResult(): mixed
    {
        return $this->execResult;
    }

    final public function getRules(): array
    {
        $hasRules = \method_exists(static::class, 'rules');
        if (!$hasRules) {
            return [];
        }
        return $this->rules();
    }

    protected function getOptions(): array
    {
        return $this->arguments;
    }

    protected function getOption(string $name): ?LightDataType
    {
        return $this->arguments[$name] ?? null;
    }

    final protected function setResult(mixed $data): void
    {
        $this->result = $data;
    }

    protected function settings(): SettingInterface
    {
        return $this->container->settings();
    }

    protected function color(): Colorizer
    {
        if (!$this->colorizer) {
            $this->colorizer = \in_array('--no-ansi', $this->basicArguments) ? new ReplacingColorizer() : new Colorizer();
        }
        return $this->colorizer;
    }

    private function convertArguments(array $arguments): array
    {
        $result = [];
        foreach ($arguments as $name => $argument) {
            if (!\is_int($name)) {
                \is_string($argument) and $argument = \trim($argument, '"');
                $result[$name] = new LightDataType($argument);
                continue;
            }
            if (\str_starts_with($argument, '-')) {
                $param = \str_contains($argument, '=') ? \strstr($argument, '=', true) : $argument;
                $name = \ltrim($param, '-');
                $value = \ltrim((\strstr($argument, '=') ?: ''), '=');
                $value = \trim($value, '"');


                if ($param !== '--' . $name && $param !== '-' . $name) {
                    continue;
                }
                if ($value === '') {
                    $result[$name] = new LightDataType(true);
                    continue;
                }


                if (\str_contains($argument, '[') && \str_ends_with($argument, ']')) {
                    $list = \array_map('trim', \explode(',', \trim($value, '[]')));
                    $result[$name] = new LightDataType($list);
                    continue;
                }
                $result[$name] = new LightDataType($value);
            }
        }
        return $result;
    }

    private function runOthers(): bool
    {
        $this->verbosity = (bool)$this->strictVerbosity;

        return $this->runCli();
    }

    private function runCli(): bool
    {
        $this->code = 1;
        $rules = [];
        $this->searchQuietAndReplace($this->basicArguments);

        $this->verbosity or \ob_start();
        $reflectionMethod = new ReflectionMethod(static::class, 'run');
        $hasRules = \method_exists(static::class, 'rules');
        $handler = new IndexedArgConverter($reflectionMethod);
        if ($hasRules) {
            $rules = $this->rules();
            try {


                $handler->checkRules($rules);
            } catch (DynamicStateException $e) {
                throw new DynamicStateException(self::RULE_ERROR . $e->getMessage());
            }
        }


        $this->arguments = $this->convertArguments($this->searchSystemParams($this->basicArguments, $rules, $handler));


        $indexedArguments = $handler->checkIndexedArgs($this->unnamedArguments);
        if ($indexedArguments === false) {
            throw new InvalidArgumentException(self::RUN_ERROR . $this->getTypeErrors($handler->getErrors()));
        }


        if ($hasRules) {


            $rules = $handler->assignmentOfShortNames($this->arguments, $rules);


            $handler->checkAssocArguments($this->arguments, $rules);
        }
        $this->execResult = $this->run(...$indexedArguments);

        $this->verbosity or \ob_get_clean();

        $this->code = \is_int($this->execResult) ? $this->execResult : 0;

        return $this->code === 0;
    }

    private function searchQuietAndReplace(array &$arguments): void
    {
        foreach ($arguments as $key => $arg) {
            if (\is_string($arg) && \trim($arg) === '--quiet') {
                $this->verbosity = false;
                unset($arguments[$key]);
            }
        }
    }

    private function searchSystemParams(array $arguments, array $rules, IndexedArgConverter $handler): array
    {
        $num = 0;
        $names = $handler->getAssigmentNames($rules);
        while (isset($arguments[$num]) && $this->checkIsUnnamed($arguments[$num], $names)) {
            $this->unnamedArguments[] = $arguments[$num];
            unset($arguments[$num]);
            $num++;
        }
        return $arguments;
    }

    private function checkIsUnnamed(string $value, $names): bool
    {
        if (\str_starts_with($value, '-')) {
            $target = \explode('=', $value)[0];
            if (\in_array($target, $names, true)) {
                return false;
            }
        }

        return true;
    }

    private function checkAttributes(): bool
    {
        if ($this->attributeHelper->hasClassAttribute(Purpose::class)) {
            $status = $this->attributeHelper->getClassValue(Purpose::class, 'status');
            if ($this->fromCli && $status === Purpose::EXTERNAL) {
                return false;
            }
            if (!$this->fromCli && $status === Purpose::CONSOLE) {
                return false;
            }
        }
        return true;
    }

    private function getTypeErrors(array $errors): string
    {
        return \implode(', ', \array_unique($errors));
    }
}
