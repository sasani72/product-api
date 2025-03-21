<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Config;
use App\Router\Router;
use App\Services\ProductService;
use App\Controllers\ProductController;
use App\Repositories\ProductRepository;

Config::load();

$repository = new ProductRepository();
$service = new ProductService($repository);
$controller = new ProductController($service);

$apiPrefix = Config::getApiConfig()['prefix'] ?? '/api/v1';
$router = new Router($apiPrefix);

$router->addRoute('GET', '/products', fn() => $controller->index());
$router->addRoute('POST', '/products', fn() => $controller->store());

$router->addRoute('GET', '/products/{id}', fn($id) => $controller->show($id));
$router->addRoute('PATCH', '/products/{id}', fn($id) => $controller->update($id));
$router->addRoute('DELETE', '/products/{id}', fn($id) => $controller->destroy($id));

try {
    $router->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (\PDOException $e) {
    $controller->jsonResponse(['error' => 'Database error'], 500);
} catch (\Exception $e) {
    $statusCode = $e->getCode() ?: 500;
    $controller->jsonResponse(['error' => $e->getMessage()], $statusCode);
}