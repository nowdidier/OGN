<?php
/*
 ┌───────────────────────────────────────────────────────────┐
 │                          ATTENTION                        │
 ├───────────────────────────────────────────────────────────┤
 │                                                           │
 │ This file is automatically generated.                     │
 │ All changes made will be lost when updating the library.  │
 │                                                           │
 │                                                           │
 ├───────────────────────────────────────────────────────────┤
 │                          ВНИМАНИЕ                         │
 ├───────────────────────────────────────────────────────────┤
 │                                                           │
 │ Этот файл сгенерирован автоматически.                     │
 │ Все внесённые изменения будут потеряны при обновлении.    │
 │                                                           │
 │                                                           │
 └───────────────────────────────────────────────────────────┘
 */

declare(strict_types=1);

namespace App\Commands\Migration;

use Hleb\Base\Task;
use Hleb\Static\DB;
use Hleb\Static\Settings;
use Phphleb\Migration\Src\MigrateException;
use Phphleb\Migration\Src\Migration;
use Throwable;

class Status extends Task
{
    /**
     * Displays statuses of migrations.
     *
     * Отображает статусы миграций.
     *
     * @throws Throwable
     */
    protected function run(): int
    {
        try {
            $dir = Settings::getPath('@global/migrations');
            $migrations = (new Migration(DB::getNewInstance(), 'migrations', $dir, true))->status();
            if ($migrations) {
                for ($i = 0; $i < count($migrations); $i++) {
                    echo PHP_EOL . ($i + 1) . '. ' . $migrations[$i];
                }
                echo PHP_EOL;
            }
        } catch (MigrateException $e) {
            echo PHP_EOL . 'ERROR: ' . $e->getMessage() . PHP_EOL;
            return self::ERROR_CODE;
        }
        return self::SUCCESS_CODE;
    }
}
