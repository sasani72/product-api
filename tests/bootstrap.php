<?php

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Config;
use App\Config\Database;

$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
$_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? '5432';
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'productdb';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'user';
$_ENV['DB_PASSWORD'] = $_ENV['DB_PASSWORD'] ?? 'password';

foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

Config::load();

try {
    $connection = Database::getConnection();
} catch (PDOException $e) {
    throw $e;
}

ob_end_clean();