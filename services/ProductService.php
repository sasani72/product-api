<?php

namespace App\Services;

use App\DTO\ProductDTO;
use App\Validators\ProductValidator;
use App\Repositories\ProductRepository;
use App\Exceptions\ValidationException;
use App\Exceptions\ProductNotFoundException;

class ProductService implements ServiceInterface
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find(string $id): ?array
    {
        $product = $this->repository->find($id);
        if (!$product) {
            throw new ProductNotFoundException("Product with ID {$id} not found");
        }
        return $product;
    }

    public function findAll(array $criteria = []): array
    {
        return $this->repository->findAll($criteria);
    }

    public function create(array $data): array
    {
        try {
            ProductValidator::validateCreate($data);
            $productDTO = ProductDTO::create($data);
            return $this->repository->create($productDTO->toArray());
        } catch (\InvalidArgumentException $e) {
            throw new ValidationException($e->getMessage());
        }
    }

    public function update(string $id, array $data): array
    {
        try {
            $existingProduct = $this->find($id);
            if (!$existingProduct) {
                throw new ProductNotFoundException("Product with ID {$id} not found");
            }

            ProductValidator::validateUpdate($data);
            $updateDTO = ProductDTO::update($data);
            
            $product = $this->repository->update($id, $updateDTO->toArray());
            if (!$product) {
                throw new ProductNotFoundException("Product with ID {$id} not found");
            }
            return $product;
        } catch (\InvalidArgumentException $e) {
            throw new ValidationException($e->getMessage());
        }
    }

    public function delete(string $id): bool
    {
        $result = $this->repository->delete($id);
        if (!$result) {
            throw new ProductNotFoundException("Product with ID {$id} not found");
        }
        return true;
    }
} 