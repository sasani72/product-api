<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\Exceptions\ValidationException;
use App\Exceptions\ProductNotFoundException;

class ProductServiceTest extends TestCase
{
    private ProductService $service;
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->service = new ProductService($this->repository);
    }

    public function testFindProduct(): void
    {
        $product = [
            'id' => '123',
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test'],
            'createdAt' => '2024-02-22T12:00:00Z'
        ];

        $this->repository->expects($this->once())
            ->method('find')
            ->with('123')
            ->willReturn($product);

        $result = $this->service->find('123');
        $this->assertEquals($product, $result);
    }

    public function testFindProductNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with('123')
            ->willReturn(null);

        $this->expectException(ProductNotFoundException::class);
        $this->service->find('123');
    }

    public function testCreateProduct(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $product = array_merge(['id' => '123', 'createdAt' => '2024-02-22T12:00:00Z'], $data);

        $this->repository->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('name'))
            ->willReturn($product);

        $result = $this->service->create($data);
        $this->assertEquals($product, $result);
    }

    public function testCreateProductValidationError(): void
    {
        $data = [
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $this->expectException(ValidationException::class);
        $this->service->create($data);
    }
} 