<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database {
  private $pdo;
  public function __construct() {
    $db = require __DIR__.'/../Config/config.php';

    try { // DB_TYPE
      $this->pdo = new PDO($db['db_type'].':dbname='.$db['dbname'].';host='.$db['host'], $db['user'], $db['pass']);
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