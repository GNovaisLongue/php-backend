<?php

namespace App\models;

use App\Db\Database;

use \PDO;

class Product {
  private $db;

  public function __construct() {
    $this->db = (new Database())->getPdo();
  }
  public function getAllProducts() {
    $stmt = $this->db->query('SELECT * FROM product');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getProductById(int|string $sku) {
    $stmt = $this->db->prepare('SELECT * FROM product WHERE sku = ?');
    $stmt->execute([$sku]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function insertProduct($newProduct) {
    if (!$newProduct) die("Product is empty!");
    $data = [
      "sku"               => $newProduct['sku'], 
      "name"              => $newProduct['name'], 
      "price"             => $newProduct['price'],
      "product_type"      => $newProduct['product_type'],
      "product_attribute" => $newProduct['product_attribute'],
    ];
    // check if exists
    $stmt = $this->db->prepare('SELECT IF(EXISTS(SELECT * FROM product WHERE sku=?), 1, 0) AS result');
    $stmt->execute([$newProduct["sku"]]);
    $exists = $stmt->fetchColumn();
    if($exists) {
      header('X-PHP-Response-Code: 400 - SKU already exists', true, 400);
      die("SKU already exists");
    } else {
      $stmt = $this->db->prepare('INSERT INTO `product` (sku, name, price, product_type, product_attribute) VALUES (:sku, :name, :price, :product_type, :product_attribute);');
      $stmt->execute($data);
      header('X-PHP-Response-Code: 200 - Successfully Inserted!', true, 200);
      die("Successfully Inserted!");
    }
  }
  public function deleteProducts($skus) {
    $placeholders = array_pad([],count($skus),'?');
    $stmt = $this->db->prepare('DELETE FROM `product` WHERE `sku` IN ('.implode(',',$placeholders).')');
    $stmt->execute($skus);
    header('X-PHP-Response-Code: 200 - Successfully Deleted!', true, 200);
    die("Successfully Deleted!");
  }
}