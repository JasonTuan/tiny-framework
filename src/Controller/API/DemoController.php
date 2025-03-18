<?php

namespace TinyFramework\Controller\API;

use TinyFramework\Models\Http\JsonResponse;

class DemoController
{
    public function sayHello(string $name): JsonResponse
    {
        return json_response([
            'say' => 'Hello ' . $name . '!',
        ]);
    }

    public function demo1(): JsonResponse
    {
        return json_response([
            'demo' => 'Demo 1',
        ]);
    }
}
