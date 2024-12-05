<?php

namespace App\controllers;

use App\models\Product;

// https://github.com/soheilkhaledabdi/php-crud-api-flight/blob/main/app/Controllers/UserController.php

class ProductController {
  // public $productModel;
  // public function __construct() {
  //   $this->productModel = new Product();
  // }

  public static function index() {
    $products = (new Product())->getAllProducts();
    // Render the product list view
    require_once __DIR__.'/../views/product_view.php';
  }
  public static function view($sku) {
    $product = (new Product())->getProductById($sku);
    // $product = $this->productModel->getProductById($sku);
    // Render the product detail view
    require_once __DIR__.'/../views/product_id_view.php';
  }
  // public function insertProduct($newProduct) {
  //   $this->productModel->insertProduct($newProduct);
  // }
  // public function deleteProducts($skus) {
  //   $this->productModel->deleteProducts($skus);
  // }
}