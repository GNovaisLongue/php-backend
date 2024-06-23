<?php

namespace App\models;

use App\db\Database;
require_once __DIR__.'/../db/Database.php';

use \PDO;

class Product {
  private $db;

  public function __construct() {
    $this->db = (new Database())->getPdo();
  }
  public function getAllProducts() {
    $stmt = $this->db->query('SELECT * FROM products;');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getProductById($sku) {
    $stmt = $this->db->prepare('SELECT * FROM `products` WHERE `sku` = :sku;');
    $stmt->execute(['sku' => $sku]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function insertProduct($newProduct) {
    if (!$newProduct) die("Product is empty!");
    $data = [
      "sku"=>$newProduct['sku'], 
      "name"=>$newProduct['name'], 
      "price"=>$newProduct['price'],
      "product_type"=>$newProduct['product_type'],
      "product_attribute"=>$newProduct['product_attribute'],
    ];
    // check if exists
    $stmt = $this->db->prepare('SELECT IF(EXISTS(SELECT * FROM `products` WHERE `sku`=:sku), 1, 0) AS result;');
    $stmt->execute(['sku' => $newProduct["sku"]]);
    $exists = $stmt->fetchColumn();
    if($exists) {
      header('X-PHP-Response-Code: 400 - SKU already exists', true, 400);
      return ("SKU already exists");
    } else {
      $stmt = $this->db->prepare('INSERT INTO `products` (sku, name, price, product_type, product_attribute) VALUES (:sku, :name, :price, :product_type, :product_attribute);');
      $stmt->execute($data);
      header('X-PHP-Response-Code: 200 - Successfully Inserted!', true, 200);
      return ("Successfully Inserted!");
    }
  }
  public function deleteProducts($skus) {
    $placeholders = array_pad([],count($skus),'?');
    $stmt = $this->db->prepare('DELETE FROM `products` WHERE `sku` IN ('.implode(',',$placeholders).')');
    $stmt->execute($skus);
    return "Successfully Deleted!";
  }
  

  //   public function insert($product)
  //   {
  //     $fields = array_keys($product);
  //     $binds = array_pad([],count($fields),'?');
  //     $query = 'INSERT INTO '.$this->table.' ('.implode(',', $fields).') VALUES ('.implode(',',$binds).')';
  //     $this->executeQuery($query,array_values($product));
  //     return "Successfully Inserted!";
  //   }

  // // Insert new product into the database
  // public function insert()
  // {
  //   $objDatabase = new Database('products');
  //   $exists = $objDatabase->exists($this->getSku());
  //   if ($exists === 1)
  //   {
  //     header('X-PHP-Response-Code: 400 - SKU already exists', true, 400);
  //     die("SKU already exists!");
  //   } else {
  //     $result = $objDatabase->insert([
  //       "sku"=>$this->getSku(),
  //       "name"=>$this->getName(),
  //       "price"=>$this->getPrice(),
  //       "product_type"=>$this->getProduct_type(),
  //       "product_attribute"=>$this->getProduct_attribute()
  //     ]);
  //     return(json_encode($result));
  //   }
  // }


  //   public function insert($product)
  //   {
  //     $fields = array_keys($product);
  //     $binds = array_pad([],count($fields),'?');
  //     $query = 'INSERT INTO '.$this->table.' ('.implode(',', $fields).') VALUES ('.implode(',',$binds).')';
  //     $this->executeQuery($query,array_values($product));
  //     return "Successfully Inserted!";
  //   }



  // //Get all products from database
  // public static function getAllProducts($where = '', $order = '', $limit = '')
  // {
  //   return (new Database('products'))->select($where,$order,$limit)->fetchAll(PDO::FETCH_ASSOC);
  // }


  // //Get product by id
  // public static function getProductById($where = '', $order = '', $limit = '')
  // {
  //   return (new Database('products'))->select($where,$order,$limit)->fetchAll(PDO::FETCH_ASSOC);
  // }


  // //Delete a product from database
  // public static function deleteProduct($data)
  // {
  //   return (new Database('products'))->delete($data);
  // }
}