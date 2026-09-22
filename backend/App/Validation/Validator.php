<?php

declare(strict_types=1);

namespace App\Validation;

use InvalidArgumentException;

/**
 * Reusable input validation + sanitisation helpers.
 * SQL injection is handled by PDO prepared statements; these helpers
 * enforce shape/type so malformed input is rejected with 422/409
 * before it ever reaches the query layer.
 */
final class Validator
{
    public static function sku(mixed $value): string
    {
        $sku = is_string($value) ? trim($value) : '';
        if ($sku === '' || strlen($sku) > 25 || !preg_match('/^[A-Za-z0-9_-]+$/', $sku)) {
            throw new InvalidArgumentException('Invalid SKU: 1-25 chars [A-Za-z0-9_-]', 422);
        }

        return $sku;
    }

    /** @return list<string> */
    public static function skuList(mixed $value, int $max = 100): array
    {
        if (!is_array($value) || $value === []) {
            throw new InvalidArgumentException('SKU list must be a non-empty array', 422);
        }
        if (count($value) > $max) {
            throw new InvalidArgumentException('SKU list exceeds ' . $max . ' items', 422);
        }

        $out = [];
        foreach (array_values($value) as $sku) {
            $out[] = self::sku($sku);
        }

        return array_values(array_unique($out));
    }

    public static function id(mixed $value): int
    {
        if (is_int($value) && $value > 0) {
            return $value;
        }
        if (is_string($value) && ctype_digit($value) && (int) $value > 0) {
            return (int) $value;
        }

        throw new InvalidArgumentException('Invalid ID: positive integer expected', 422);
    }

    public static function nonEmptyString(mixed $value, string $field, int $maxLen = 255): string
    {
        $str = is_string($value) ? trim($value) : '';
        if ($str === '' || strlen($str) > $maxLen) {
            throw new InvalidArgumentException("Invalid {$field}: 1-{$maxLen} chars expected", 422);
        }

        return $str;
    }

    public static function email(mixed $value): string
    {
        $email = is_string($value) ? trim($value) : '';
        if (strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Invalid email address', 422);
        }

        return $email;
    }

    public static function price(mixed $value): string
    {
        $price = is_string($value) || is_int($value) || is_float($value)
            ? trim((string) $value)
            : '';
        if ($price === '' || strlen($price) > 25 || !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
            throw new InvalidArgumentException('Invalid price: numeric, max 2 decimals', 422);
        }

        return $price;
    }

    /**
     * @param array<string,mixed> $input
     * @param list<string> $required
     * @return array<string,mixed>
     */
    public static function requireFields(array $input, array $required): array
    {
        foreach ($required as $field) {
            if (!array_key_exists($field, $input) || $input[$field] === null || $input[$field] === '') {
                throw new InvalidArgumentException("Missing required field: {$field}", 422);
            }
        }

        return $input;
    }
}
