<?php

namespace App\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\Response;

interface TransformerInterface
{
    public function __invoke(Response $response): mixed;

    public function getType(): string;
}
