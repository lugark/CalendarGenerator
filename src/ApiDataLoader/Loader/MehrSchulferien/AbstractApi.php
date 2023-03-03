<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\RequestInterface;

abstract class AbstractApi
{
    const API_URL = 'https://www.mehr-schulferien.de/api/v2.0/';

    public RequestInterface $curlRequest;

    public function __construct(RequestInterface $curlRequest)
    {
        $this->curlRequest = $curlRequest;
    }

    public function getApiUrl(): String
    {
        return self::API_URL . $this->getApiSubPath();
    }

    abstract public function getApiSubPath(): String;
}
