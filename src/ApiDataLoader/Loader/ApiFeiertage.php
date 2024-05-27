<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Transformer\ApiFeiertageTransformer;
use App\ApiDataLoader\Transformer\TransformerInterface;

class ApiFeiertage implements LoaderInterface
{
    public const LOADER_TYPE = 'api_feiertage';
    public const DEUTSCHE_FEIERTAGE_URL = 'https://get.api-feiertage.de?years=';

    public function __construct(
        private readonly RequestInterface $curlRequest, 
        private readonly ApiFeiertageTransformer $transformer
    ) {
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
