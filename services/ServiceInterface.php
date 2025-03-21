<?php

namespace App\Services;

interface ServiceInterface
{
    public function find(string $id): ?array;
    public function findAll(array $criteria = []): array;
    public function create(array $data): array;
    public function update(string $id, array $data): array;
    public function delete(string $id): bool;
} 