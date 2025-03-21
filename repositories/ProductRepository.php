<?php

namespace App\Repositories;

use PDO;
use App\Config\Database;

class ProductRepository implements RepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function find(string $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        
        if (!$result) {
            return null;
        }

        return $this->formatProduct($result);
    }

    public function findAll(array $criteria = []): array
    {
        $sql = "SELECT * FROM products";
        $params = [];
        $conditions = [];

        $allowedCriteria = [
            'price_min' => function($value) use (&$conditions, &$params) {
                $conditions[] = "price >= :price_min";
                $params['price_min'] = $value;
            },
            'price_max' => function($value) use (&$conditions, &$params) {
                $conditions[] = "price <= :price_max";
                $params['price_max'] = $value;
            },
            'category' => function($value) use (&$conditions, &$params) {
                $conditions[] = "category = :category";
                $params['category'] = $value;
            }
        ];

        foreach ($criteria as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (isset($allowedCriteria[$key])) {
                $allowedCriteria[$key]($value);
            }
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        return array_map([$this, 'formatProduct'], $results);
    }

    public function create(array $data): array
    {
        $sql = "INSERT INTO products (name, price, category, attributes)
                VALUES (:name, :price, :category, :attributes)
                RETURNING *";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'category' => $data['category'],
            'attributes' => json_encode($data['attributes']),
        ]);

        return $this->formatProduct($stmt->fetch());
    }

    public function update(string $id, array $data): ?array
    {
        $allowedFields = ['name', 'price', 'category', 'attributes'];
        $updates = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowedFields)) {
                continue;
            }

            $updates[] = "{$key} = :{$key}";
            $params[$key] = $key === 'attributes' ? json_encode($value) : $value;
        }

        if (empty($updates)) {
            return null;
        }

        $updates[] = "updated_at = CURRENT_TIMESTAMP";
        $sql = "UPDATE products SET " . implode(", ", $updates) . " WHERE id = :id RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result ? $this->formatProduct($result) : null;
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function formatProduct(array $data): array
    {
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'price' => (float) $data['price'],
            'category' => $data['category'],
            'attributes' => json_decode($data['attributes'], true),
            'createdAt' => $data['created_at'],
            'updatedAt' => $data['updated_at']
        ];
    }
} 