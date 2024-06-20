<?php

require __DIR__."/vendor/autoload.php";

use App\model\Product;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');//CORS

$method = $_SERVER['REQUEST_METHOD'];
if ($method) {
  switch($method)
  {
    case "GET":
      $products = Product::getAllProducts();
      die(json_encode($products));
  
    // !!! 000webhost doesn't allow/recognize 'DELETE' method. Using POST to delete as well
    case "POST":
      $product = json_decode(file_get_contents("php://input"));
  
      if (is_object($product) === true) // Adding new product
      {
        $objProduct = new Product;
        $objProduct->setSku($product->sku);
        $objProduct->setName($product->name);
        $objProduct->setPrice($product->price);
        $objProduct->setProduct_attribute($product->product_attribute);
        $objProduct->setProduct_type($product->product_type);
  
        die($objProduct->insertProduct());
      } elseif (is_array($product) === true) {
  
        if(!$product) die("DELETE - Empty deletion string");
  
        die(Product::deleteProduct($product));
      } else 
      {
        die("Failed to parse content");
      }
  
    default:
      die("SOMETHING WENT WRONG. Wasn't supposed to end here. index.php");
  }
} else {
  echo "<p>Welcome to PHP</p>";
}
