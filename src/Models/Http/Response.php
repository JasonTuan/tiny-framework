<?php

namespace TinyFramework\Models\Http;

class Response
{
    public const int HTTP_OK = 200;
    public const int HTTP_CREATED = 201;
    public const int HTTP_ACCEPTED = 202;
    public const int HTTP_BAD_REQUEST = 400;
    public const int HTTP_UNAUTHORIZED = 401;
    public const int HTTP_FORBIDDEN = 403;
    public const int HTTP_NOT_FOUND = 404;
    public const int HTTP_INTERNAL_SERVER_ERROR = 500;

    public string $viewTemplate = '';
    public array $viewParams = [];

    public mixed $body;
    public array $headers = [];
    public int $httpCode = self::HTTP_OK;

    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function getHeader(string $key, string $defaultValue): string
    {
        return $this->headers[$key] ?? $defaultValue;
    }

    public function setHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function setContent(mixed $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function setView(string $viewTemplate, array $viewParams = []): self
    {
        $this->viewTemplate = $viewTemplate;
        $this->viewParams = $viewParams;

        return $this;
    }

    protected function buildHeaders(): void
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        http_response_code($this->httpCode);
    }
}
