<?php

// Database connection class

namespace App\db;

use \PDO;
use \PDOException;

class Database {
  private $pdo;
  public function __construct() {
    $config = require __DIR__.'/../config/config.php';
    $db = $config['db'];

    try {
      $this->pdo = new PDO('mysql:dbname='.$db['dbname'].';host='.$db['host'], $db['user'], $db['pass']);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      // header('X-PHP-Response-Code: 404 - Failed to connect', true, 404);
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public function getPdo() {
    return $this->pdo;
  }
}