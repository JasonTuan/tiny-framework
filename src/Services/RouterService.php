<?php

namespace TinyFramework\Services;

use TinyFramework\Models\Http\Router;

class RouterService
{
    private array $routes = [];

    public ?Router $currentRoute;

    public function __construct()
    {
        $this->routes = $this->getRoutes();
        $this->currentRoute = $this->matchRoute();
    }

    public function route(string $routeName, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($route->name === $routeName) {
                return $route->generate($params);
            }
        }

        return '#';
    }

    private function matchRoute(): ?Router
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
            if ($route->match($path, $method)) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Get routes
     *
     * @return array<Router>
     */
    public function getRoutes(): array
    {
        $routeData = require __DIR__ . '/../route.php';

        return $this->parserRouter($routeData);
    }

    private function parserRouter(
        array $routeData,
        ?string $routeName = '',
        ?string $routePath = '',
        ?string $controller = null,
        ?array $middlewares = [],
    ): array
    {
        $routes = [];
        foreach ($routeData as $name => $item) {
            $routeItemName = $routeName . $name;
            $routeItemPath = $routePath;
            $middlewaresItem = $middlewares;
            if (!empty($item['path'])) {
                $routeItemPath .= $item['path'];
            }
            if (!empty($item['controller'])) {
                $controller = $item['controller'];
            }
            if (!empty($item['middlewares'])) {
                $middlewaresItem = array_merge($middlewaresItem, $item['middlewares']);
            }
            if (isset($item['groups'])) {
                $routesGroup = $this->parserRouter($item['groups'], $routeItemName, $routeItemPath, $controller, $middlewaresItem);
                $routes = array_merge($routes, $routesGroup);
            } else {
                $router = new Router();
                $router->name = $routeItemName;
                $router->controller = $controller;
                $router->path = $routeItemPath;
                $router->method = $item['method'];
                $router->action = $item['action'] ?? null;
                $router->middlewares = $middlewaresItem;
                $routes[] = $router;
            }
        }

        return $routes;
    }
}
