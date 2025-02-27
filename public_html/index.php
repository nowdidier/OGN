<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

// All requests are directed to this file.
// Все запросы направляются в этот файл.

define('HLEB_START', microtime(true));
define('HLEB_PUBLIC_DIR', realpath(__DIR__));
define('HLEB_GLOBAL_DIR', realpath(__DIR__ . '/../'));

// Root template folder on the system 
// Корневая папка шаблонов в системе
define('TEMPLATES', realpath(HLEB_GLOBAL_DIR . '/resources/views'));

// General headers.
// Общие заголовки.
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-Powered-By: LibArea');

// Initialization.
try {
    require __DIR__ . '/../vendor/phphleb/framework/bootstrap.php';
} catch (\Throwable $e) {
    // ...existing error logging or handling...
    http_response_code(500);
    echo "An error occurred. Please try again later.";
    exit;
}
