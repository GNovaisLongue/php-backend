<?php

namespace App\controllers;

use App\models\Product;
require_once __DIR__.'/../models/Product.php';

class ProductController {
  private $productModel;
  public function __construct() {
    $this->productModel = new Product();
  }
  public function index() {
    $products = $this->productModel->getAllProducts();
    // Render the product list view
    require_once __DIR__.'/../views/product_view.php';
  }
  public function view($sku) {
    $product = $this->productModel->getProductById($sku);
    // Render the product detail view
    require_once __DIR__.'/../views/product_id_view.php';
  }
  public function insertProduct($newProduct) {
    $this->productModel->insertProduct($newProduct);
  }
  public function deleteProducts($skus) {
    $this->productModel->deleteProducts($skus);
  }
}