<?php

namespace App\models;

use App\Db\Database;
use \PDO;

class User {
  private $db;

  public function __construct() {
    $this->db = (new Database())->getPdo();
  }
  public function getAllUsers() {
    $stmt = $this->db->query('SELECT * FROM user;');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getUserById($id) {
    $stmt = $this->db->prepare('SELECT * FROM `user` WHERE `id` = :id;');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function insertUser($newUser) {
    if (!$newUser) die("User data is empty!");
    $data = [
      "name"              => $newUser['name'],
      "email"             => $newUser['email'],
      "role"              => $newUser['role'],
      "date_created"      => $newUser['date_created'],
      "date_last_updated" => $newUser['date_last_updated'],
    ];
    // Check if email already exists
    $stmt = $this->db->prepare('SELECT IF(EXISTS(SELECT * FROM `user` WHERE `email` = :email), 1, 0) AS result;');
    $stmt->execute(['email' => $newUser["email"]]);
    $exists = $stmt->fetchColumn();
    if ($exists) {
      header('X-PHP-Response-Code: 400 - Email already exists', true, 400);
      die("Email already exists");
    } else {
      $stmt = $this->db->prepare('INSERT INTO `user` (name, email, role, date_created, date_last_updated) VALUES (:name, :email, :role, :date_created, :date_last_updated);');
      $stmt->execute($data);
      header('X-PHP-Response-Code: 200 - User Successfully Inserted!', true, 200);
      die("User Successfully Inserted!");
    }
  }
  public function deleteUser($id) {
    $stmt = $this->db->prepare('DELETE FROM `user` WHERE `id` = :id;');
    $stmt->execute(['id' => $id]);
    header('X-PHP-Response-Code: 200 - User Successfully Deleted!', true, 200);
    die("User Successfully Deleted!");
  }
}
