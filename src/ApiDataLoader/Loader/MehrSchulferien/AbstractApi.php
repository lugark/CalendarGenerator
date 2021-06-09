<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\CurlLoaderTrait;

abstract class AbstractApi
{
    use CurlLoaderTrait;

    const API_URL = 'https://www.mehr-schulferien.de/api/v2.0/';

    public function getApiUrl(): String
    {
        return self::API_URL . $this->getApiSubPath();
    }

    abstract public function getApiSubPath(): String;
}