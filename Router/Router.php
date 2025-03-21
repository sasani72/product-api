<?php

namespace App\Router;

class Router
{
    private array $routes = [];
    private string $apiPrefix;

    public function __construct(string $apiPrefix = '/api/v1')
    {
        $this->apiPrefix = $apiPrefix;
    }

    /**
     * Add a new route to the router
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $path URL path
     * @param callable $handler Function to handle the route
     */
    public function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Handle the current request
     * 
     * @param string $method Current HTTP method
     * @param string $uri Current request URI
     * @return void
     */
    public function handle(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace($this->apiPrefix, '', $path);

        foreach ($this->routes as $route) {
            $pattern = str_replace('{id}', '([^/]+)', $route['path']);
            if (preg_match("#^{$pattern}$#", $path, $matches) && $route['method'] === $method) {
                array_shift($matches);
                $route['handler'](...$matches);
                return;
            }
        }

        throw new \Exception('Not found', 404);
    }
} 