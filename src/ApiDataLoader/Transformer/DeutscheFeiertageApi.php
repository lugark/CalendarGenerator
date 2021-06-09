<?php

namespace App\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\Response;

class DeutscheFeiertageApi implements TransformerInterface
{
    const TRANSFORMER_TYPE = 'deutsche_feiertage_api';

    public function __invoke(Response $response)
    {
        $data = $response->getData();
        if (!$response->isSuccess() || !isset($data['result'])) {
            return [];
        }
        
        foreach ($data['holidays'] as $key => $holiday) {
            $regions = [];
            foreach ($holiday['holiday']['regions'] as $region => $hasHoliday) {
                $region = $region === 'bay' ? 'BY' : strtoupper($region);
                if ($hasHoliday) {
                    $regions[] = $region;
                }
            }
            $data['holidays'][$key]['holiday']['regions'] = $regions;
        }
        return $data['holidays'];
    }

    public function getType(): string
    {
        return self::TRANSFORMER_TYPE;
    }
}