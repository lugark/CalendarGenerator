<?php

namespace App\ApiDataLoader\Loader;

class DeutscheFeiertageApi implements LoaderInterface
{
    use CurlLoaderTrait;

    const LOADER_TYPE = 'deutsche_feiertage_api';
    const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';

    public function fetch(string $year): Response
    {
        return $this->executeCurl(
            self::DEUTSCHE_FEIERTAGE_URL . $year,   
            [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'X-DFA-Token: dfa']
            ]);
    }

    public function getType(): String
    {
        return self::LOADER_TYPE;
    }
}