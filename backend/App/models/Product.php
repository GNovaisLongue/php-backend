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
      die("SKU already exists");
    } else {
      $stmt = $this->db->prepare('INSERT INTO `products` (sku, name, price, product_type, product_attribute) VALUES (:sku, :name, :price, :product_type, :product_attribute);');
      $stmt->execute($data);
      header('X-PHP-Response-Code: 200 - Successfully Inserted!', true, 200);
      die("Successfully Inserted!");
    }
  }
  public function deleteProducts($skus) {
    $placeholders = array_pad([],count($skus),'?');
    $stmt = $this->db->prepare('DELETE FROM `products` WHERE `sku` IN ('.implode(',',$placeholders).')');
    $stmt->execute($skus);
    header('X-PHP-Response-Code: 200 - Successfully Deleted!', true, 200);
    die("Successfully Deleted!");
  }
}