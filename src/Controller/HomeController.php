<?php

namespace TinyFramework\Controller;

use Carbon\Carbon;
use TinyFramework\Models\Http\HtmlResponse;

class HomeController
{
    public function index(): HtmlResponse
    {
        return render('Home/index.tpl', [
            'name' => 'Demo',
        ]);
    }
}
