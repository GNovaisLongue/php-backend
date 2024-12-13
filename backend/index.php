<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Http\Router;

// Load ENV variables
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../'); // when executed locally
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // when executed via docker or other similar service
$dotenv->load();

// Define the base URL dynamically
if (isset($_ENV['URL']) && !empty($_ENV['URL'])) {
  define('URL', $_ENV['URL']);
} else {
  // Automatically detect host if URL is not set
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
  define('URL', rtrim($protocol . '://' . $host . $scriptDir, '/'));
}

$obRouter = new Router(URL);
include __DIR__.'/App/routes/routes.php';
$obRouter->run();

// echo '<pre>';
// var_dump($db);
// echo '</pre>';
