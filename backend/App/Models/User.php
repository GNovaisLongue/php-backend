<?php

declare(strict_types=1);

namespace App\Models;

use App\Db\Database;
use App\Validation\Validator;
use PDO;
use RuntimeException;

/**
 * User repository (Repository pattern).
 * All SQL uses prepared statements with bound parameters only.
 */
class User
{
    private PDO $db;

    private const ALLOWED_ROLES = ['admin' => true, 'user' => true, 'verified' => true];

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? Database::getInstance()->getPdo();
    }

    /** @return list<array<string,mixed>> */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query(
            'SELECT `id`, `name`, `email`, `role`, `date_created`, `date_last_updated` FROM `user` ORDER BY `id` ASC'
        );

        return $stmt->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public function getUserById(int|string $id): ?array
    {
        $id = Validator::id($id);

        $stmt = $this->db->prepare(
            'SELECT `id`, `name`, `email`, `role`, `date_created`, `date_last_updated` FROM `user` WHERE `id` = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array<string,mixed> $newUser
     * @return int inserted ID
     */
    public function insertUser(array $newUser): int
    {
        Validator::requireFields($newUser, ['name', 'email', 'role']);

        $role = Validator::nonEmptyString($newUser['role'], 'role', 20);
        if (!isset(self::ALLOWED_ROLES[strtolower($role)])) {
            throw new \InvalidArgumentException('Invalid role: admin, user, verified expected', 422);
        }

        $data = [
            ':name'  => Validator::nonEmptyString($newUser['name'], 'name', 100),
            ':email' => Validator::email($newUser['email']),
            ':role'  => $role,
        ];

        $exists = $this->db->prepare('SELECT 1 FROM `user` WHERE `email` = :email LIMIT 1');
        $exists->execute([':email' => $data[':email']]);
        if ($exists->fetchColumn() !== false) {
            throw new RuntimeException('Email already exists', 409);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO `user` (`name`, `email`, `role`, `date_created`, `date_last_updated`)'
            . ' VALUES (:name, :email, :role, NOW(), NOW())'
        );
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    /** @return int deleted row count (0 or 1) */
    public function deleteUser(int|string $id): int
    {
        $id = Validator::id($id);

        $stmt = $this->db->prepare('DELETE FROM `user` WHERE `id` = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount();
    }
}
