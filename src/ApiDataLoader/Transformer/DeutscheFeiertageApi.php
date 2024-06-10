<?php

namespace App\ApiDataLoader\Transformer;

use App\ApiDataLoader\Loader\DeutscheFeiertageApi as DeutscheFeiertageApiLoader;
use App\ApiDataLoader\Loader\Response;

class DeutscheFeiertageApi implements TransformerInterface
{
    public function __invoke(Response $response)
    {
        $data = $response->getData();
        if (!$response->isSuccess() || !isset($data['result'])) {
            return [];
        }
        
        foreach ($data['holidays'] as $key => $holiday) {
            $regions = [];
            foreach ($holiday['holiday']['regions'] as $region => $hasHoliday) {
                $region = $region === 'bay' ? 'BY' : strtoupper((string) $region);
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
        return DeutscheFeiertageApiLoader::LOADER_TYPE;
    }
}