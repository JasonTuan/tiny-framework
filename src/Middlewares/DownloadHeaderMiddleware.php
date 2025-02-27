<?php

namespace TinyFramework\Middlewares;

use TinyFramework\Models\Http\HtmlResponse;
use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\Http\Request;

class DownloadHeaderMiddleware implements MiddlewareInterface
{

    public function handle(Request $request, HtmlResponse|JsonResponse $response): HtmlResponse|JsonResponse
    {
        $response->setHeader('Content-Disposition', 'attachment; filename="downloaded.txt"');

        return $response;
    }
}
