<?php

namespace App\ApiDataLoader\Loader\MehrSchulferien;

use App\ApiDataLoader\Loader\Response;

class Periods extends AbstractApi
{
    const PERIODS_PATH = 'periods';
    const TYPES_FIELD = 'holiday_or_vacation_type_id';
    const LOCATIONS_FIELD = 'location_id';

    public function getApiSubPath(): string
    {
        return self::PERIODS_PATH;
    }

    public function getAllPeriods(): Response
    {
        return $this->curlRequest->execute(
            $this->getApiUrl(),
            [
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ]
            ]);
    }

}
