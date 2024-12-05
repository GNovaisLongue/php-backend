<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Http\Router;

// Load ENV variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
// define URL without the need for _ENV
define('URL', $_ENV['URL']);

//No need for DB start here
// require __DIR__.'/app/Config/database.php';
// $db = (new Database())->getPdo();

// echo '<pre>';
// var_dump($db);
// echo '</pre>';

$obRouter = new Router(URL);
include __DIR__.'/app/routes/routes.php';
$obRouter->run()->sendResponse();

