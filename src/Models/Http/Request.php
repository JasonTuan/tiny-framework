<?php

namespace TinyFramework\Models\Http;

class Request
{
    public string $path;
    public string $method;
    public array $params;
    public array $headers;
    public array $cookies;
    public array $routerParams = [];

    public function __construct()
    {
        $this->path = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = $_REQUEST;
        $this->cookies = $_COOKIE;
        $this->headers = getallheaders();

        $rawInput = file_get_contents("php://input");
        if ($rawInput) {
            $rawInput = json_decode($rawInput, true);
            if (is_array($rawInput)) {
                $this->params = array_merge($this->params, $rawInput);
            }
        }
    }

    public function getHeader(string $header, mixed $defaultValue = null): mixed
    {
        return getValue($this->headers, $header, $defaultValue);
    }

    public function getRouterParam(string $param, mixed $defaultValue = null): mixed
    {
        return getValue($this->routerParams, $param, $defaultValue);
    }

    public function get(string $param, mixed $defaultValue = null, mixed $enumName = null): mixed
    {
        $value = getValue($this->params, $param, $defaultValue);
        if (!empty($value) && $enumName && enum_exists($enumName)) {
            try {
                return $enumName::from(strtoupper($value));
            } catch (\Error $e) {
                return $defaultValue;
            }
        }

        return $value;
    }
}
