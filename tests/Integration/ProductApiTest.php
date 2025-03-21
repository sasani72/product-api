<?php

namespace Tests\Integration;

use App\Config\Database;
use PHPUnit\Framework\TestCase;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\Exceptions\ProductNotFoundException;

class ProductApiTest extends TestCase
{
    private ProductService $service;
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new ProductRepository();
        $this->service = new ProductService($this->repository);
    }

    protected function tearDown(): void
    {
        $db = Database::getConnection();
        $db->exec('TRUNCATE TABLE products CASCADE');
        Database::closeConnection();
    }

    public function testCreateAndRetrieveProduct(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $product = $this->service->create($data);
        
        $this->assertArrayHasKey('id', $product);
        $this->assertArrayHasKey('createdAt', $product);
        $this->assertEquals($data['name'], $product['name']);
        $this->assertEquals($data['price'], $product['price']);
        $this->assertEquals($data['category'], $product['category']);
        $this->assertEquals($data['attributes'], $product['attributes']);

        $retrieved = $this->service->find($product['id']);
        $this->assertEquals($product, $retrieved);
    }

    public function testUpdateProduct(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $product = $this->service->create($data);
        
        $updateData = [
            'price' => 150.75,
            'attributes' => ['brand' => 'Updated Brand']
        ];

        $updated = $this->service->update($product['id'], $updateData);
        
        $this->assertEquals($updateData['price'], $updated['price']);
        $this->assertEquals($updateData['attributes'], $updated['attributes']);
        $this->assertEquals($product['name'], $updated['name']);
        $this->assertEquals($product['category'], $updated['category']);
    }

    public function testDeleteProduct(): void
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50,
            'category' => 'electronics',
            'attributes' => ['brand' => 'Test']
        ];

        $product = $this->service->create($data);
        
        $this->assertTrue($this->service->delete($product['id']));
        
        $this->expectException(ProductNotFoundException::class);
        $this->service->find($product['id']);
    }

    public function testListProductsWithFilters(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'price' => 100.50,
                'category' => 'electronics',
                'attributes' => ['brand' => 'Brand 1']
            ],
            [
                'name' => 'Product 2',
                'price' => 200.75,
                'category' => 'electronics',
                'attributes' => ['brand' => 'Brand 2']
            ],
            [
                'name' => 'Product 3',
                'price' => 150.25,
                'category' => 'clothing',
                'attributes' => ['brand' => 'Brand 3']
            ]
        ];

        foreach ($products as $product) {
            $this->service->create($product);
        }

        $filtered = $this->service->findAll([
            'category' => 'electronics',
            'price_min' => 100,
            'price_max' => 150
        ]);

        $this->assertCount(1, $filtered);
        $this->assertEquals('Product 1', $filtered[0]['name']);
    }
}