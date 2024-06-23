<?php

use App\routes\Web;
require_once __DIR__.'/App/routes/web.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');//CORS

$method = $_SERVER['REQUEST_METHOD'];
if ($method)
{
  $webRoute = new Web($method);
} else {
  $webRoute = new Web("GET");
}