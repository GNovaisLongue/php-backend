<?php

namespace App\routes;

use App\controllers\ProductController;
require_once __DIR__.'/../controllers/ProductController.php';

class Web {
  public function __construct($method) {

    $controller = new ProductController();

    switch ($method) {
      case 'GET':
        if(isset($_GET['sku'])) {
          $controller->view($_GET['sku']);
        } else {
          $controller->index();
        }
        break;
      case "POST":
        if (isset($_POST)) {
          $controller->insertProduct($_POST);
        } else {
          die(header('X-PHP-Response-Code: 404 - Failed to parse POST content', true, 404));
        }
        break;
      case "PUT":
        $product = json_decode(file_get_contents("php://input"), true);
        if (isset($product)) {
          $controller->insertProduct($product);
        } else {
          die(header('X-PHP-Response-Code: 404 - Failed to parse PUT content', true, 404));
        }
        break;
      case 'DELETE': //REQUEST_METHOD doesn't have DELETE request; recognizes as POST
        $skus = json_decode(file_get_contents("php://input"), true);
        if(isset($skus)) {
          $controller->deleteProducts($skus["sku"]);
        } else {
          die(header('X-PHP-Response-Code: 404 - Failed to parse DELETE content', true, 404));
        }
        break;
      default:
        die("SOMETHING WENT WRONG. Wasn't supposed to end here. web.php");
    }
  }
}