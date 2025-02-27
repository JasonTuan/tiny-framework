<?php

namespace TinyFramework\Models\Http;

interface ResponseInterface
{
    public function send(): void;
}
