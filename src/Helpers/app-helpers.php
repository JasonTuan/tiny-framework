<?php

use TinyFramework\App;
use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;
use TinyFramework\Services\DBService;
use TinyFramework\Services\RouterServices;
use Smarty\Smarty;

function app(): App
{
    global $app;
    return $app;
}

function config(): array
{
    return app()->getConfig();
}

function view(): Smarty
{
    return app()->view;
}

function request(): Request
{
    return app()->request;
}

function router(): RouterServices
{
    return app()->router;
}

function csrf(): string
{
    return app()->getToken();
}

function checkCsrf(?string $token): bool
{
    return app()->checkToken($token);
}

function db(): DBService
{
    return app()->db;
}

function dd(...$args): void
{
    if (!empty($args)) {
        echo '<pre>';
        var_dump(...$args);
        echo '</pre>';
    }
    die;
}

function getValue(array $params, string $param, mixed $defaultValue = null): mixed
{
    $value = $params[$param] ?? $defaultValue;
    if ($value === 'false' || $value === 'true') {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    } elseif (is_numeric($value)) {
        $value = intval($value);
    }

    return $value;
}

function render(string $template, array $params = [], int $httpCode = 200): HtmlResponse
{
    $response = new HtmlResponse();
    $response->setHttpCode($httpCode);
    $response->viewParams = $params;
    $response->viewTemplate = $template;

    return $response;
}

function json_response(?array $data = null, int $status = 200): JsonResponse
{
    $response = new JsonResponse();
    $response->setHttpCode($status);
    $response->body = $data;

    return $response;
}
