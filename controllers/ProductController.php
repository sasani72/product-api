<?php

namespace App\Controllers;

use App\Services\ProductService;

class ProductController extends BaseController
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        try {
            $criteria = [
                'category' => $_GET['category'] ?? null,
                'price_min' => $_GET['price_min'] ?? null,
                'price_max' => $_GET['price_max'] ?? null,
            ];

            $criteria = array_filter($criteria);
            $products = $this->service->findAll($criteria);
            $this->jsonResponse($products);
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    public function show(string $id): void
    {
        try {
            $product = $this->service->find($id);
            $this->jsonResponse($product);
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->getRequestData();
            $product = $this->service->create($data);
            $this->jsonResponse($product, 201);
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    public function update(string $id): void
    {
        try {
            $data = $this->getRequestData();
            $product = $this->service->update($id, $data);
            $this->jsonResponse($product);
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    public function destroy(string $id): void
    {
        try {
            $this->service->delete($id);
            $this->jsonResponse(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }
} 