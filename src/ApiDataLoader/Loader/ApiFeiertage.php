<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Transformer\ApiFeiertageTransformer;
use App\ApiDataLoader\Transformer\TransformerInterface;

class ApiFeiertage implements LoaderInterface
{
    const LOADER_TYPE = 'api_feiertage';
    const DEUTSCHE_FEIERTAGE_URL = 'https://get.api-feiertage.de?years=';

    private ApiFeiertageTransformer $transformer;
    private RequestInterface $curlRequest;

    public function __construct(RequestInterface $curlRequest, ApiFeiertageTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->curlRequest = $curlRequest;
    }

    public function getType(): String
    {
        return self::LOADER_TYPE;
    }

    public function getTransformer(): ?TransformerInterface
    {
        return $this->transformer;
    }

    public function fetchData(string $year): Response
    {
        return $this->curlRequest->execute(
            self::DEUTSCHE_FEIERTAGE_URL . $year,
            [
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ]
            ]
        );
    }
}
