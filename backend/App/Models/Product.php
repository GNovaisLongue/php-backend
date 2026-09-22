<?php

declare(strict_types=1);

namespace App\Models;

use App\Db\Database;
use App\Validation\Validator;
use InvalidArgumentException;
use PDO;
use RuntimeException;

/**
 * Product repository (Repository pattern).
 *
 * All SQL uses prepared statements with bound parameters only —
 * no string interpolation of user input. Identifiers are fixed
 * whitelisted column lists.
 */
class Product
{
    private PDO $db;

    private const TABLE = 'product';

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? Database::getInstance()->getPdo();
    }

    /** @return list<array<string,mixed>> */
    public function getAllProducts(): array
    {
        $stmt = $this->db->query(
            'SELECT `sku`, `name`, `price`, `product_type`, `product_attribute` FROM `product` ORDER BY `sku` ASC'
        );

        return $stmt->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public function getProductById(int|string $sku): ?array
    {
        $sku = Validator::sku($sku);

        $stmt = $this->db->prepare(
            'SELECT `sku`, `name`, `price`, `product_type`, `product_attribute` FROM `product` WHERE `sku` = :sku'
        );
        $stmt->execute([':sku' => $sku]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array<string,mixed> $newProduct
     * @return string inserted SKU
     */
    public function insertProduct(array $newProduct): string
    {
        Validator::requireFields($newProduct, ['sku', 'name', 'price', 'product_type', 'product_attribute']);

        $data = [
            ':sku'               => Validator::sku($newProduct['sku']),
            ':name'              => Validator::nonEmptyString($newProduct['name'], 'name', 100),
            ':price'             => Validator::price($newProduct['price']),
            ':product_type'      => Validator::nonEmptyString($newProduct['product_type'], 'product_type', 25),
            ':product_attribute' => Validator::nonEmptyString($newProduct['product_attribute'], 'product_attribute', 25),
        ];

        $exists = $this->db->prepare('SELECT 1 FROM `product` WHERE `sku` = :sku LIMIT 1');
        $exists->execute([':sku' => $data[':sku']]);
        if ($exists->fetchColumn() !== false) {
            throw new RuntimeException('SKU already exists', 409);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO `product` (`sku`, `name`, `price`, `product_type`, `product_attribute`)'
            . ' VALUES (:sku, :name, :price, :product_type, :product_attribute)'
        );
        $stmt->execute($data);

        return $data[':sku'];
    }

    /**
     * Mass delete by SKU list. Named placeholders are generated
     * server-side; values are bound positionally via execute().
     *
     * @param list<string>|array<mixed> $skus
     * @return int deleted row count
     */
    public function deleteProducts(array $skus): int
    {
        $skus = Validator::skuList($skus);

        $placeholders = [];
        $bindings = [];
        foreach ($skus as $i => $sku) {
            $key = ':sku' . $i;
            $placeholders[] = $key;
            $bindings[$key] = $sku;
        }

        $stmt = $this->db->prepare(
            'DELETE FROM `product` WHERE `sku` IN (' . implode(',', $placeholders) . ')'
        );
        $stmt->execute($bindings);

        return $stmt->rowCount();
    }

    /** @return int deleted row count (0 or 1) */
    public function deleteProduct(int|string $sku): int
    {
        $sku = Validator::sku($sku);

        $stmt = $this->db->prepare('DELETE FROM `product` WHERE `sku` = :sku');
        $stmt->execute([':sku' => $sku]);

        return $stmt->rowCount();
    }
}
