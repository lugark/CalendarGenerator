<?php

namespace App\ApiDataLoader\Loader;

use App\ApiDataLoader\Transformer\TransformerInterface;

class DeutscheFeiertageApi implements LoaderInterface
{
    public const LOADER_TYPE = 'deutsche_feiertage_api';
    public const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';

    public function __construct(private readonly RequestInterface $curlRequest)
    {
    }

    public function getTransformer(): ?TransformerInterface
    {
        return new \App\ApiDataLoader\Transformer\DeutscheFeiertageApi();
    }

    public function fetchData(string $year): Response
    {
        return $this->curlRequest->execute(
            self::DEUTSCHE_FEIERTAGE_URL . $year,
            [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'X-DFA-Token: dfa']
            ]
        );
    }

    public function getType(): String
    {
        return self::LOADER_TYPE;
    }
}
