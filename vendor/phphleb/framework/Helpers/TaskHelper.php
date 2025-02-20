<?php


namespace Hleb\Helpers;

use FilesystemIterator;
use Hleb\Constructor\Attributes\Accessible;
use Hleb\Constructor\Attributes\Disabled;
use Hleb\Constructor\Attributes\Hidden;
use Hleb\Constructor\Attributes\Task\Purpose;
use Hleb\CoreProcessException;
use Hleb\Static\Settings;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[Accessible]
final class TaskHelper
{

    public function checkName(string $str): bool
    {
        return \strlen($str) && \preg_match('/^[a-zA-Z0-9\/\-\:\.\_]+$/', $str);
    }

    public function getDuplicateName(array $list): array
    {
        $countValues = \array_count_values($list);
        $duplicates = [];
        foreach ($countValues as $key => $value) {
            if ($value > 1) {
                $duplicates[] = $key;
            }
        }
        return $duplicates;
    }

    public function getCommands(bool $withHidden = false): array
    {
        $dir = Settings::getRealPath('@app/Commands');
        $tasks = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );
        $list = [];

        foreach ($tasks as $key => $task) {
            if (!$task->isFile() || !str_ends_with($task->getRealPath(), '.php')) {
                continue;
            }
            $path = \strstr(\ltrim(\str_replace($dir, '', $task->getRealPath(), $count), '\\/'), '.php', true);

            if ($count > 1) {
                continue;
            }
            $class = 'App\Commands\\' . \str_replace(DIRECTORY_SEPARATOR, '\\', $path);
            if (!$this->isVisibility($class)) {
                continue;
            }
            if (!$withHidden && $this->isHidden($class)) {
                continue;
            }
            $list[$key]['class'] = $class;
            $list[$key]['path'] = $path;

            $data = (new ClassDataInFile($task->getRealPath()));
            $class = $data->getClass();
            $constants = (new ReflectionConstant($class))->all();
            $name = $constants['TASK_NAME'] ?? null;
            if ($name) {
                $list[$key]['name'] = (string)$name;
            }
            $short = $constants['TASK_SHORT_NAME'] ?? null;
            if ($short) {
                $list[$key]['short'] = (string)$short;
            }
            if (!$name) {
                $parts = \explode(DIRECTORY_SEPARATOR, $path);
                $converter = new NameConverter();
                foreach ($parts as $k => $part) {
                    $parts[$k] = $converter->convertClassNameToStr($part);
                }
                $command = \implode('/', $parts);
                $list[$key]['name'] = $command;
            }
        }

        return $list;
    }

    public function isVisibility(string $class): bool
    {
        $helper = new AttributeHelper($class);


        if ($helper->hasClassAttribute(Disabled::class) ||
            ($helper->hasClassAttribute(Purpose::class) &&
                $helper->getClassValue(Purpose::class, 'status') === Purpose::EXTERNAL
            )
        ) {
            return false;
        }
        return true;
    }

    public function isHidden(string $class): bool
    {
        return (new AttributeHelper($class))->hasClassAttribute(Hidden::class);
    }
}
