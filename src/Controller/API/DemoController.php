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
}
