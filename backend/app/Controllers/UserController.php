<?php

namespace App\controllers;

use App\models\User;

class UserController {
  // private $userModel;
  // public function __construct() {
  //   $this->userModel = new User();
  // }

  public static function index() {
    $products = (new User())->getAllUsers();
    print_r($products);
    exit;
  }
  public static function view($sku) {
    $product = (new User())->getUserById($sku);
    die($product);
  }
  // public function insertUser($newUser) {
  //   $this->userModel->insertUser($newUser);
  // }
  // public function deleteUser($id) {
  //   $this->userModel->deleteUser($id);
  // }
}