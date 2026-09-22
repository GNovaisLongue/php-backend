<?php

declare(strict_types=1);

$autoload = __DIR__ . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
} else {
    // Fallback PSR-4 autoloader when composer install was skipped.
    spl_autoload_register(static function (string $class): void {
        if (!str_starts_with($class, 'App\\')) {
            return;
        }
        $path = __DIR__ . '/App/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($path)) {
            require_once $path;
        }
    });
}

use App\Http\Router;

// Base URL: explicit URL env wins, otherwise auto-detect (docker / php -S / apache).
$baseUrl = $_ENV['URL'] ?? getenv('URL');
if (!is_string($baseUrl) || $baseUrl === '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : $scriptDir;
    $baseUrl = rtrim($protocol . '://' . $host . $scriptDir, '/');
}
define('URL', $baseUrl);

$obRouter = new Router(URL);
require __DIR__ . '/App/routes/routes.php';
$obRouter->run();
