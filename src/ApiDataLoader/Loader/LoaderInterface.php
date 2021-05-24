<?php

namespace App\ApiDataLoader\Loader;

interface LoaderInterface
{
    public function fetch(string $year): Response;
    public function getType(): String;
}
