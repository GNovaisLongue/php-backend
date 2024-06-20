<?php

namespace App\model;

use App\db\Database;

use \PDO;

class Product
{
  private $sku, $name, $price, $product_type, $product_attribute;

  public function getSku() 
  {
    return $this->sku;
  }
  public function setSku($sku) 
  {
    $this->sku = $sku;
  }

  public function getName() 
  {
    return $this->name;
  }
  public function setName($name) 
  {
    $this->name = $name;
  }

  public function getPrice() 
  {
    return $this->price;
  }
  public function setPrice($price) 
  {
    $this->price = $price;
  }

  public function getProduct_type() 
  {
    return $this->product_type;
  }
  public function setProduct_type($product_type) 
  {
    $this->product_type = $product_type;
  }

  public function getProduct_attribute() 
  {
    return $this->product_attribute;
  }
  public function setProduct_attribute($product_attribute) 
  {
    $this->product_attribute = $product_attribute;
  }

  // Insert new product into the database
  public function insertProduct()
  {
    $objDatabase = new Database('products');

    $exists = $objDatabase->exists($this->getSku());
    if ($exists === 1)
    {
      header('X-PHP-Response-Code: 400 - SKU already exists', true, 400);
      die("SKU already exists!");
    } else {
      $result = $objDatabase->insert([
        "sku"=>$this->getSku(),
        "name"=>$this->getName(),
        "price"=>$this->getPrice(),
        "product_type"=>$this->getProduct_type(),
        "product_attribute"=>$this->getProduct_attribute()
      ]);
  
      return(json_encode($result));
    }

  }

  //Get all products from database
  public static function getAllProducts($where = '', $order = '', $limit = '')
  {
    return (new Database('products'))->select($where,$order,$limit)->fetchAll(PDO::FETCH_ASSOC);
  }
  
  //Delete a product from database
  public static function deleteProduct($data)
  {
    return (new Database('products'))->delete($data);
  }
}