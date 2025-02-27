<?php

namespace TinyFramework\Middlewares;

use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;

class AllowOriginMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, HtmlResponse|JsonResponse $response): HtmlResponse|JsonResponse
    {
        $response->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Credentials', 'true')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding')
            ->setHeader('Access-Control-Max-Age', '3600');

        return $response;
    }
}
