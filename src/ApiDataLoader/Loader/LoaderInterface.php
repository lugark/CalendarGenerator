<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Transformer\TransformerInterface;

interface LoaderInterface
{
    public function getType(): String;

    public function fetchData(string $year): Response;

    public function getTransformer(): ?TransformerInterface;
}
