<?php

declare(strict_types=1);

// Centralised, env-tolerant configuration.
// Accepts both current (.env) and legacy (.env.example) key names.

$dbType  = $_ENV['DB_TYPE'] ?? getenv('DB_TYPE') ?: 'mysql';
$host    = $_ENV['MYSQL_HOST'] ?? getenv('MYSQL_HOST') ?: 'mysql';
$dbname  = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE')
    ?? $_ENV['MYSQL_DB_DSN'] ?? getenv('MYSQL_DB_DSN') ?: 'ecommerce_products';
$user    = $_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER')
    ?? $_ENV['MYSQL_DB_USER'] ?? getenv('MYSQL_DB_USER') ?: 'mysql';
$pass    = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD')
    ?? $_ENV['MYSQL_DB_PASSWD'] ?? getenv('MYSQL_DB_PASSWD') ?: 'root';
$charset = $_ENV['MYSQL_DB_CHARSET'] ?? getenv('MYSQL_DB_CHARSET') ?: 'utf8mb4';

return [
    'db_type' => strtolower((string) $dbType) === 'pgsql' ? 'pgsql' : 'mysql',
    'host'    => (string) $host,
    'dbname'  => (string) $dbname,
    'user'    => (string) $user,
    'pass'    => (string) $pass,
    'charset' => (string) $charset,
];
