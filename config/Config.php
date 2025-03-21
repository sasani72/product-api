<?php

namespace App\Config;

class Config
{
    private static array $config = [];

    public static function load(): void
    {
        self::$config['DB_HOST'] = getenv('DB_HOST') ?: 'localhost';
        self::$config['DB_PORT'] = getenv('DB_PORT') ?: '5432';
        self::$config['DB_NAME'] = getenv('DB_NAME') ?: 'productdb';
        self::$config['DB_USER'] = getenv('DB_USER') ?: 'user';
        self::$config['DB_PASSWORD'] = getenv('DB_PASSWORD') ?: 'password';
    }

    public static function getDatabaseConfig(): array
    {
        return [
            'host' => self::$config['DB_HOST'],
            'port' => self::$config['DB_PORT'],
            'dbname' => self::$config['DB_NAME'],
            'user' => self::$config['DB_USER'],
            'password' => self::$config['DB_PASSWORD']
        ];
    }

    public static function getApiConfig(): array
    {
        return [
            'host' => self::$config['API_HOST'] ?? '0.0.0.0',
            'port' => self::$config['API_PORT'] ?? '8080'
        ];
    }
} 