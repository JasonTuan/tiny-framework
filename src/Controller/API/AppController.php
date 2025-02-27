<?php

namespace TinyFramework\Controller\API;

use TinyFramework\Models\Http\JsonResponse;

class AppController
{
    public function getToken(): JsonResponse
    {
        return json_response(['token' => csrf()]);
    }
}
