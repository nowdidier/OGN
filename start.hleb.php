<?php

declare(strict_types=1);

define('HLEB_GLOBAL_DIR', __DIR__);
define('HLEB_PUBLIC_DIR', realpath(HLEB_GLOBAL_DIR));
define('HLEB_PROJECT_DEBUG', true);

require HLEB_GLOBAL_DIR . '/vendor/autoload.php';

// Initialize the framework
(new \Hleb\Main\MainAutoloader())->autoloader();

// Start the application
(new \Hleb\Main\Console\MainConsole())->execute(__DIR__);
