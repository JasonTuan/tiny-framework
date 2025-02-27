<?php
namespace TinyFramework\Models\Http;

use JetBrains\PhpStorm\NoReturn;
use ReflectionMethod;
use Smarty\Exception;

class Router
{
    public string $name;
    public mixed $controller;
    public string $path;
    public array $params = [];
    public string $method;
    public ?string $action;

    public array $middlewares = [];

    /**
     * @throws Exception
     * @throws \ReflectionException
     */
    #[NoReturn] public function handle(): void
    {
        if ($this->controller instanceof \Closure) {
            $controller = $this->controller;
            $response = $controller();
        } else {
            $controllerInstance = new $this->controller();
            $action = $this->action ?? 'index';
            if (!method_exists($controllerInstance, $action)) {
                throw new Exception('Action not found');
            }
            $reflectionMethod = new ReflectionMethod($this->controller, $action);
            if (count($this->params) > 0) {
                $response = $reflectionMethod->invokeArgs($controllerInstance, $this->params);
            } else {
                $response = $reflectionMethod->invoke($controllerInstance);
            }
        }

        $response = $this->handleMiddlewares($response);

        $response->send();
    }

    public function match(string $path, string $method): bool
    {
        $routeParts = explode('/', $this->path);
        foreach ($routeParts as $key => $part) {
            if (str_starts_with($part, '{?') && str_ends_with($part, '}')) {
                $this->params[substr($part, 2, -1)] = null;
                $routeParts[$key] = '<?>';
            } elseif (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $this->params[substr($part, 1, -1)] = null;
                $routeParts[$key] = '<>';
            }
        }
        $pathPattern = '/^' . preg_quote(implode('/', $routeParts), "/") . '(\/)?(\?(.*))?$/';
        $pathPattern = str_replace('\<\>', '(.*)', $pathPattern);
        $pathPattern = str_replace('\<\?\>', '(.*)?', $pathPattern);

        if (!preg_match($pathPattern, $path)) {
            return false;
        }

        preg_match_all($pathPattern, $path, $matches);
        $i = 1;
        foreach ($this->params as $key => $value) {
            if (isset($matches[$i])) {
                $matchValue = $matches[$i][0];
                if (str_contains($matchValue, '?')) {
                    $matchValue = explode('?', $matchValue)[0];
                }
                $this->params[$key] = $matchValue;
            }
            $i++;
        }

        return $this->method === $method;
    }

    public function generate(array $params = []): string
    {
        $path = $this->path;
        $other = [];
        foreach ($params as $key => $value) {
            if (!str_contains($path, '{' . $key . '}') && !str_contains($path, '{?' . $key . '}')) {
                $other[$key] = $value;
                continue;
            }
            $path = str_replace('{' . $key . '}', $value, $path);
            $path = str_replace('{?' . $key . '}', $value, $path);
        }

        return empty($other) ? $path : $path . '?' . http_build_query($other);
    }

    public function getParam(string $param, mixed $defaultValue = null): mixed
    {
        return getValue($this->params, $param, $defaultValue);
    }

    protected function handleMiddlewares(HtmlResponse|JsonResponse $response): HtmlResponse|JsonResponse
    {
        foreach ($this->middlewares as $middleware) {
            $middleware = new $middleware();
            $response = $middleware->handle(request(), $response);
        }

        return $response;
    }
}

