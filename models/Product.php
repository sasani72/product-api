<?php

namespace App\Models;

use Ramsey\Uuid\UuidInterface;

class Product
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly string $name,
        public readonly float $price,
        public readonly string $category,
        public readonly array $attributes,
        public readonly \DateTimeImmutable $createdAt
    ) {}

    public static function create(
        string $name,
        float $price,
        string $category,
        array $attributes = []
    ): self {
        return new self(
            id: \Ramsey\Uuid\Uuid::uuid4(),
            name: $name,
            price: $price,
            category: $category,
            attributes: $attributes,
            createdAt: new \DateTimeImmutable()
        );
    }
} 