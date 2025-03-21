<?php

namespace App\Validators;

use App\DTO\ProductDTO;

class ProductValidator
{
    public static function validateCreate(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Name is required');
        }

        if (!isset($data['price']) || !is_numeric($data['price'])) {
            throw new \InvalidArgumentException('Price must be a numeric value');
        }

        if (empty($data['category'])) {
            throw new \InvalidArgumentException('Category is required');
        }

        if (isset($data['attributes']) && !is_array($data['attributes'])) {
            throw new \InvalidArgumentException('Attributes must be an array');
        }
    }

    public static function validateUpdate(array $data): void
    {
        if (isset($data['name']) && empty($data['name'])) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if (isset($data['price']) && !is_numeric($data['price'])) {
            throw new \InvalidArgumentException('Price must be a numeric value');
        }

        if (isset($data['category']) && empty($data['category'])) {
            throw new \InvalidArgumentException('Category cannot be empty');
        }

        if (isset($data['attributes']) && !is_array($data['attributes'])) {
            throw new \InvalidArgumentException('Attributes must be an array');
        }
    }
} 