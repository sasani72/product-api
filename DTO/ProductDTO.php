<?php

namespace App\DTO;

class ProductDTO
{
    private readonly ?string $name;
    private readonly ?float $price;
    private readonly ?string $category;
    private readonly ?array $attributes;
    private readonly ?string $id;

    private function __construct(
        ?string $name = null,
        ?float $price = null,
        ?string $category = null,
        ?array $attributes = null,
        ?string $id = null
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->attributes = $attributes;
        $this->id = $id;
    }

    public static function create(array $data): self
    {
        return new self(
            name: $data['name'],
            price: (float) $data['price'],
            category: $data['category'],
            attributes: $data['attributes'] ?? [],
            id: $data['id'] ?? null
        );
    }

    public static function update(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            category: $data['category'] ?? null,
            attributes: $data['attributes'] ?? null,
            id: $data['id'] ?? null
        );
    }

    public function toArray(): array
    {
        $data = [];
        
        if ($this->name !== null) $data['name'] = $this->name;
        if ($this->price !== null) $data['price'] = $this->price;
        if ($this->category !== null) $data['category'] = $this->category;
        if ($this->attributes !== null) $data['attributes'] = $this->attributes;
        if ($this->id !== null) $data['id'] = $this->id;
        
        return $data;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
} 