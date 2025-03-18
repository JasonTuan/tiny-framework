<?php

use TinyFramework\App;
use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;
use TinyFramework\Services\DBService;
use TinyFramework\Services\RouterService;
use Smarty\Smarty;
use TinyFramework\Support\Collection;

function app(): App
{
    global $app;
    return $app;
}

function config(?string $key = null): mixed
{
    $configs = app()->getConfig();
    return !empty($key)
        ? Collection::getArrayValue(
            path: $key,
            items: $configs,
        )
        : $configs;
}

function getConfigPath(string $path, ?string $defaultFromStorage = null): string
{
    $path = config($path);

    if ((!$path || resolvePath($path) === null) && $defaultFromStorage) {
        return storePath($defaultFromStorage);
    } elseif (!$path || resolvePath($path) === null) {
        throw new Exception('Path not found');
    }

    return resolvePath($path);
}

function resolvePath(string $path, bool $silentMode = true): ?string
{
    if (realpath($path) === false && !$silentMode) {
        throw new Exception('Path not found');
    }

    return realpath($path) ? realpath($path) . DIRECTORY_SEPARATOR : null;
}

function view(): Smarty
{
    return app()->view;
}

function request(): Request
{
    return app()->request;
}

function router(): RouterService
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

function collect(array $data = []): Collection
{
    return new Collection($data);
}

function db(): DBService
{
    return app()->db;
}

function storePath(?string $path = null, bool $forceCreate = false): string
{
    $storagePath = realpath(__DIR__ . '/../../storages/');
    if ($storagePath === false) {
        throw new Exception('Storage path not found');
    }

    if (!is_readable($storagePath) || !is_writeable($storagePath)) {
        throw new Exception('Storage path is not readable or writable');
    }

    if (empty($path)) {
        return $storagePath . DIRECTORY_SEPARATOR;
    } else {
        $path = $storagePath . DIRECTORY_SEPARATOR . $path;
        if (!is_dir($path) && $forceCreate) {
            mkdir($path, 0777, true);
        } elseif (!is_dir($path)) {
            throw new Exception('Path not found');
        }
    }

    return $path . DIRECTORY_SEPARATOR;
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

function json_response(null|array|object $data = null, int $status = 200): JsonResponse
{
    $response = new JsonResponse();
    $response->setHttpCode($status);
    $response->body = $data;

    return $response;
}

function abort(string $message, int $status = 500): JsonResponse
{
    $response = new JsonResponse();
    $response->setHttpCode($status);
    $response->body = [
        'code' => $status,
        'error' => $message,
    ];

    return $response;
}

if (!function_exists('guid')) {
    function guid()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}
