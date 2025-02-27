<?php

namespace TinyFramework\Models\Http;

use JetBrains\PhpStorm\NoReturn;

class JsonResponse extends Response implements ResponseInterface
{
    public function __construct() {
        $this->setHeader('Content-Type', 'application/json');
    }

    #[NoReturn] public function send(): void
    {
        $this->buildHeaders();

        if (!empty($this->body)) {
            echo json_encode($this->body);
        }
        exit;
    }
}
