<?php

namespace App\ApiDataLoader\Loader;

interface RequestInterface
{
    /**
     * @param array<mixed> $options
     */
    public function execute(string $url, array $options): Response;
}
