<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

class Locations extends AbstractApi
{
    const LOCATION_PATH='locations';

    protected array $locations = [];

    public function getApiSubPath(): string
    {
        return self::LOCATION_PATH;
    }

    public function getLocation(int $id): array
    {
        if (!isset($this->locations[$id])) {
            $response =  $this->executeCurl(
                $this->getApiUrl() . '/' . $id,
                [
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        ]
                ]);

            $this->locations[$id] = $response->getData()['data'];
        }

        return $this->locations[$id];
    }
}