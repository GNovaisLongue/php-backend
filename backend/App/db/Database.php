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

// -------------------------------------------------------
//          TO ADD
//   public function executeQuery($query, $params = [])
//   {
//     try
//     {
//       $statement = $this->connection->prepare(trim($query));
//       $statement->execute($params);
//       return $statement;
//     } catch(PDOException $e) {
//       header('X-PHP-Response-Code: 400 - Failed to execute query', true, 400);
//       die("Failed to execute query ".$query." </br> { </br>".$e->getMessage()." </br> }");
//     }
//   }

//   public function exists($param)
//   {
//     $query = 'select if(exists(select * from products where sku="'.$param.'"), 1, 0) as result;';
//     return $this->executeQuery($query)->fetchColumn();
//   }

//   public function insert($product)
//   {
//     $fields = array_keys($product);
//     $binds = array_pad([],count($fields),'?');
//     $query = 'INSERT INTO '.$this->table.' ('.implode(',', $fields).') VALUES ('.implode(',',$binds).')';
//     $this->executeQuery($query,array_values($product));
//     return "Successfully Inserted!";
//   }

//   public function select($where = '', $order = '', $limit = '', $fields = '*')
//   {
//     $where = strlen($where) ? 'WHERE '.$where : '';
//     $order = strlen($order) ? 'ORDER BY '.$order : '';
//     $limit = strlen($limit) ? 'LIMIT '.$limit : '';
//     $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;
//     return $this->executeQuery($query);
//   }

//   public function delete($data)
//   {
//     $binds = array_pad([],count($data),'?');
//     $query = 'DELETE FROM '.$this->table.' WHERE `sku` IN ('.implode(',',$binds).')';
//     $this->executeQuery($query,$data);
//     return "Successfully Deleted!";
//   }
// }