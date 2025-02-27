<?php

namespace TinyFramework\Models\Http;

use JetBrains\PhpStorm\NoReturn;
use Smarty\Exception;

class HtmlResponse extends Response implements ResponseInterface
{
    public function __construct() {
        $this->setHeader('Content-Type', 'text/html');
    }

    /**
     * @throws Exception
     */
    #[NoReturn] public function send(): void
    {
        $this->buildHeaders();

        $view = view();
        $view->assign($this->viewParams);
        $view->display($this->viewTemplate);
    }
}
