<?php

namespace TinyFramework\Middlewares;

use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, HtmlResponse|JsonResponse $response): HtmlResponse|JsonResponse;
}
