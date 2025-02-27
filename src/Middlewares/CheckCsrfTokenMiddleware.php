<?php

namespace TinyFramework\Middlewares;

use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;

class CheckCsrfTokenMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, HtmlResponse|JsonResponse $response): HtmlResponse|JsonResponse
    {
        $token = request()->get('token');
        if (!checkCsrf($token)) {
            return json_response(['error' => 'Invalid token'], 403);
        }

        return $response;
    }
}
