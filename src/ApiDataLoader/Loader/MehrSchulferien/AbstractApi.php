<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\RequestInterface;

abstract class AbstractApi
{
    public const API_URL = 'https://www.mehr-schulferien.de/api/v2.0/';

    public function __construct(
        public RequestInterface $curlRequest
    )
    {
    }

    public function getApiUrl(): String
    {
        return self::API_URL . $this->getApiSubPath();
    }

    abstract public function getApiSubPath(): String;
}
