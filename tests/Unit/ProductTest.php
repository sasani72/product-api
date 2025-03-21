<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_can_create_product(): void
    {
        $product = Product::create(
            name: 'Test Product',
            price: 100.50,
            category: 'electronics',
            attributes: ['brand' => 'Test']
        );

        $this->assertNotNull($product->id);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(100.50, $product->price);
        $this->assertEquals('electronics', $product->category);
        $this->assertEquals(['brand' => 'Test'], $product->attributes);
        $this->assertInstanceOf(\DateTimeImmutable::class, $product->createdAt);
    }
}