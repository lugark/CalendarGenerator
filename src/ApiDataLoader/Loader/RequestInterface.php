<?php

namespace App\ApiDataLoader\Loader;

interface RequestInterface
{
    public function execute(string $url, array $options): Response;
}
