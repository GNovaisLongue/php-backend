<?php

declare(strict_types=1);

namespace App\Db;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?self $instance = null;

    private PDO $pdo;

    private function __construct()
    {
        /** @var array{db_type:string,host:string,dbname:string,user:string,pass:string,charset:string} $db */
        $db = require __DIR__ . '/../Config/config.php';

        $allowedTypes = ['mysql' => true, 'pgsql' => true];
        $type = strtolower($db['db_type']);
        if (!isset($allowedTypes[$type])) {
            throw new RuntimeException('Unsupported DB_TYPE: ' . $type, 500);
        }

        $dsn = sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
            $type,
            $db['host'],
            $db['dbname'],
            $db['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Never leak credentials; log detail server-side only.
            error_log('Database connection failed: ' . $e->getMessage());
            throw new RuntimeException('Database connection failed', 500, $e);
        }
    }

    private function __clone(): void
    {
    }

    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize Database singleton');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** @internal For tests only — drops the shared instance. */
    public static function reset(): void
    {
        self::$instance = null;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
