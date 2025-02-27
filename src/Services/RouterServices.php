<?php

namespace TinyFramework\Services;

use TinyFramework\Models\Http\Router;

class RouterServices
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
        $routes = [];
        foreach ($routeData as $name => $item) {
            $controllerGroup = null;
            $middlewareGroup = [];
            if (!empty($item['controller'])) {
                $controllerGroup = $item['controller'];
            }
            if (!empty($item['middlewares'])) {
                $middlewareGroup = $item['middlewares'];
            }
            if (isset($item['groups'])) {
                foreach ($item['groups'] as $groupKey => $group) {
                    $middlewareItem = $middlewareGroup;
                    $controllerItem = $controllerGroup;
                    if (!empty($group['middlewares'])) {
                        $middlewareItem = array_merge($middlewareItem, $group['middlewares']);
                    }
                    if (!empty($group['controller'])) {
                        $controllerItem = $group['controller'];
                    }
                    $router = new Router();
                    $router->name = $name . $groupKey;
                    $router->controller = $controllerItem;
                    $router->path = $item['path'] . $group['path'];
                    $router->method = $group['method'];
                    $router->action = $group['action'] ?? null;
                    $router->middlewares = $middlewareItem;
                    $routes[] = $router;
                }
            } else {
                $router = new Router();
                $router->name = $name;
                $router->controller = $controllerGroup;
                $router->path = $item['path'];
                $router->method = $item['method'];
                $router->action = $item['action'] ?? null;
                $router->middlewares = $middlewareGroup;
                $routes[] = $router;
            }
        }

        return $routes;
    }
}
